from SPARQLWrapper import SPARQLWrapper, JSON
import json
import os
import time

# Function to fetch data for the West Bank and Gaza Strip
def fetch_wb_gaza_data(location_type, offset=0, limit=50):
    query = f"""
    SELECT DISTINCT ?item ?itemLabel ?coordinates WHERE {{
      ?item wdt:P31 wd:{location_type};
            wdt:P625 ?coordinates;
            wdt:P17 wd:Q219060.  # Country: State of Palestine
      VALUES ?region {{ wd:Q36678 wd:Q39760 }}  # West Bank and Gaza
      ?item (wdt:P131*) ?region.
      SERVICE wikibase:label {{ bd:serviceParam wikibase:language "ar,en". }}
    }}
    LIMIT {limit} OFFSET {offset}
    """
    sparql = SPARQLWrapper("https://query.wikidata.org/sparql")
    sparql.setTimeout(60)
    sparql.setQuery(query)
    sparql.setReturnFormat(JSON)
    return sparql.query().convert()

# Function to fetch data for Israel (excluding specific regions)
def fetch_israel_data(location_type, offset=0, limit=50):
    query = f"""
    SELECT DISTINCT ?item ?itemLabel ?coordinates WHERE {{
      ?item wdt:P31 wd:{location_type};
            wdt:P625 ?coordinates;
            wdt:P17 wd:Q801.  # Country: Israel
      MINUS {{ ?item (wdt:P131*) wd:Q513200. }}  # Exclude Judea & Samaria Area
      MINUS {{ ?item (wdt:P131*) wd:Q83210. }}   # Exclude Golan Heights
      SERVICE wikibase:label {{ bd:serviceParam wikibase:language "ar,en". }}
    }}
    LIMIT {limit} OFFSET {offset}
    """
    sparql = SPARQLWrapper("https://query.wikidata.org/sparql")
    sparql.setTimeout(60)
    sparql.setQuery(query)
    sparql.setReturnFormat(JSON)
    return sparql.query().convert()

# Function to retry a request if it fails
def fetch_data_with_retry(fetch_function, location_type, offset=0, limit=50, retries=3):
    for attempt in range(retries):
        try:
            results = fetch_function(location_type, offset, limit)
            if attempt > 0:
                print(f"Retry {attempt} for {location_type} was successful!")
            return results
        except Exception as e:
            print(f"Retry {attempt + 1} for {location_type} failed: {e}")
            if attempt < retries - 1:
                time.sleep(5)  # Wait before retrying
    print(f"Skipping {location_type} after {retries} failed attempts.")
    return None  # Return None if all retries fail

# Fetch all data for a given type and fetch function
def fetch_all_data(fetch_function, location_type):
    all_data = []
    processed_qids = set()  # Track QIDs to avoid duplicates
    offset = 0
    limit = 50
    while True:
        try:
            results = fetch_data_with_retry(fetch_function, location_type, offset, limit)
            if not results:
                break  # Skip this batch if retries failed
            bindings = results["results"]["bindings"]
            if not bindings:
                break  # Exit loop if no more results
            for result in bindings:
                qid = result.get("item", {}).get("value", "").split("/")[-1]  # Extract QID from URL
                if qid not in processed_qids:
                    processed_qids.add(qid)
                    all_data.append({
                        "qid": qid,
                        "name": result.get("itemLabel", {}).get("value", ""),
                        "coordinates": result.get("coordinates", {}).get("value", "")
                    })
            offset += limit
            time.sleep(1)  # Delay between batches
        except Exception as e:
            print(f"An unexpected error occurred while fetching {location_type}: {e}")
            break
    return all_data

# Save results to JSON file
def save_results(data):
    laravel_root = os.path.abspath(os.path.join(os.path.dirname(__file__), "../"))
    output_dir = os.path.join(laravel_root, "storage/app")
    os.makedirs(output_dir, exist_ok=True)
    output_path = os.path.join(output_dir, "locations.json")

    with open(output_path, "w", encoding="utf-8") as file:
        json.dump(data, file, ensure_ascii=False, indent=4)
    print(f"Data saved to {output_path}")

# Main script
def main():
    types = {
        "City": "Q515",         # City
        "Town": "Q3957",        # Town
        "Village": "Q532"       # Village
    }

    all_data = []

    # Fetch data for West Bank and Gaza
    print("Fetching locations in the West Bank and Gaza Strip...")
    for type_name, type_qid in types.items():
        print(f"Fetching {type_name}s in the West Bank and Gaza...")
        data = fetch_all_data(fetch_wb_gaza_data, type_qid)
        if data:
            all_data.extend(data)
        else:
            print(f"Data for {type_name}s in the West Bank and Gaza could not be fetched and was skipped.")

    # Fetch data for Israel
    print("Fetching locations in Israel...")
    for type_name, type_qid in types.items():
        print(f"Fetching {type_name}s in Israel...")
        data = fetch_all_data(fetch_israel_data, type_qid)
        if data:
            all_data.extend(data)
        else:
            print(f"Data for {type_name}s in Israel could not be fetched and was skipped.")

    # Sort all data alphabetically by name
    all_data = sorted(all_data, key=lambda x: x["name"])

    # Save the results
    save_results(all_data)

if __name__ == "__main__":
    main()
