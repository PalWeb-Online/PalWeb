@extends ('layouts.main')

@section('content')

    <style>
        #main {
            margin-top: 0;
            padding: 3.2rem;
        }

        .head {
            display: flex;
            flex-flow: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.6rem;
            gap: 0.4rem
        }

        .head > *:nth-child(1) {
            text-transform: uppercase;
            font-family: 'JetBrains Mono', monospace;
            font-size: 3.2rem;
            font-weight: 700
        }

        a {
            color: var(--grn);
            text-decoration: underline;
        }

        h1 {
            display: flex;
            flex-flow: row wrap;
            align-items: flex-end;
            justify-content: flex-start;
            gap: 1.6rem;

            font-size: 2.8rem;
            border-bottom: 3px solid black;
            margin: 4.8rem 0 0;
        }

        h1 > * {
            font-family: "JetBrains Mono", monospace, 'IBM Plex Sans Arabic';
        }

        h1 > *:not(:first-child) {
            font-size: 2.4rem;
            color: var(--dimgrn);
            padding-bottom: 0.2rem
        }

        h2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 2.0rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', sans-serif;
            margin: 2.4rem 0 0;
        }

        h2 > *:nth-child(1) {
            flex: 1 0 40%;
        }

        h2 > *:nth-child(2) {
            font-size: 1.6rem;
        }

        h3 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0.4rem 0 0;
            gap: 0.8rem;
            text-transform: none;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 1.8rem;
            font-weight: 400;
        }

        h3 > *:nth-child(1) {
            font-style: italic;
        }

        .skills {
            display: flex;
            flex-flow: row wrap;
            justify-content: flex-end;
            gap: 0.8rem;
        }

        .skills > * {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            background: var(--grn);
            padding: 0.4rem 0.8rem;
            border-radius: 1.2rem;
            text-transform: none;
        }

        .skill-item {
            display: flex;
            flex-flow: row;
            align-items: center;
            background: var(--dimgrn);
            padding: 0;
            border: 2px solid var(--grn);
            overflow: hidden;
        }

        .skill-item > * {
            padding: 0.2rem 0.8rem;
        }

        .skill-item > div:nth-child(1) {
            background: var(--grn);
        }

        .skill-item > div:nth-child(2) {
            color: var(--grn);
            /*background: var(--dimgrn);*/
        }

        p {
            font-size: 1.6rem;
            margin-block-start: 0.4rem;
            text-indent: 1.6rem;
        }

        .sub-entries {
            margin-left: 3.2rem
        }

        .sub-entries h3 {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.6rem;
            text-transform: uppercase;
            margin-top: 1.6rem
        }

        .sub-entries h3 > *:nth-child(1) {
            font-style: normal;
        }

        ul {
            padding-left: 1.6rem
        }

        li {
            padding: 0;
            margin: 0.4rem 0;
        }
    </style>

    <div class="head">
        <div>adrian mark lore</div>
        <a href="mailto:adrian@abdulbaha.xyz">adrian@abdulbaha.xyz</a>
        <div>+1 (760)-413-4032</div>
    </div>

    {{--        EDUCATION --}}
    <h1>
        <div>Education</div>
        <div>Educación</div>
        <div>Éducation</div>
        <div>教育</div>
        <div>تعليم</div>
        <div>آموزش</div>
        <div>կրթություն</div>
    </h1>

    <h2>
        <div>University of Notre Dame</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>BA Anthropology & Peace Studies</div>
        <div>May 2020</div>
    </h3>

    <h2>
        <div>SOAS University of London</div>
        <div>London, UK</div>
    </h2>
    <h3>
        <div>MA Near & Middle Eastern Studies (with Distinction)</div>
        <div>July 2022</div>
    </h3>

    {{--        SKILLS --}}
    <h1>
        <div>Skills</div>
        <div>Habilidades</div>
        <div>Compétences</div>
        <div>能力</div>
        <div>مهارات</div>
        <div>հմտություններ</div>
    </h1>

    <h2>
        <div>languages</div>
        <div class="skills">
            <div class="skill-item">
                <div>English</div>
                <div>NAT</div>
            </div>
            <div class="skill-item">
                <div>Spanish</div>
                <div>NAT</div>
            </div>
            <div class="skill-item">
                <div>Arabic</div>
                <div>C1</div>
            </div>
            <div class="skill-item">
                <div>French</div>
                <div>B2</div>
            </div>
            <div class="skill-item">
                <div>Japanese</div>
                <div>B2</div>
            </div>
            <div class="skill-item">
                <div>Persian</div>
                <div>B1</div>
            </div>
            <div class="skill-item">
                <div>Armenian</div>
                <div>A2</div>
            </div>
        </div>
    </h2>
    <h2>
        <div>Software</div>
        <div class="skills">
            <div>Photoshop</div>
            <div>InDesign</div>
            <div>Premier Pro</div>
            <div>DaVinci Resolve</div>
        </div>
    </h2>
    <h2>
        <div>Coding</div>
        <div class="skills">
            <div>HTML</div>
            <div>CSS</div>
            <div class="skill-item">
                <div>JS</div>
                <div>Vue</div>
            </div>
            <div class="skill-item">
                <div>PHP</div>
                <div>Laravel</div>
            </div>
            <div>mySQL</div>
            <div>Python</div>
            <div>Wiki</div>
        </div>
    </h2>

    <h1>
        <div>Projects</div>
        <div>Proyectos</div>
        <div>Projets</div>
        <div>事業</div>
        <div>مشاريع</div>
        <div>پروژه‌ها</div>
        <div>նախագիծներ</div>
    </h1>

    <h2>
        <div>it-Tafsīr</div>
    </h2>
    <h3>
        <div>Founder & Director</div>
        <div>May 2020 — Present</div>
    </h3>
    <p>As the founder & director of it-Tafsir, my mission has been to document & teach Palestinian Arabic across
        multiple
        platforms, working to ensure that the language & culture are accurately represented & easily accessible to
        learners
        worldwide.</p>

    <div class="sub-entries">
        <h3>
            <div>Video Production & Hosting</div>
        </h3>
        <ul>
            <li>Established a successful YouTube channel, producing & hosting weekly videos focused on Palestinian
                Arabic.
            </li>
            <li>Cultivated an engaging & informative secondary channel dedicated to Palestine & its history.</li>
        </ul>

        <h3>
            <div>Arabic Language Instruction</div>
        </h3>
        <ul>
            <li>Created & published a variety of learning resources via Patreon.</li>
            <li>Provided one-on-one language instruction, effectively improving students’ language skills.</li>
            <li>Developed & launched a comprehensive online curriculum for learning Palestinian Arabic, catering to
                beginners
                & intermediate learners, to be used by self-learners or for individual & group lessons.
            </li>
        </ul>

        <h3>
            <div>Web Development & Project Management</div>
        </h3>
        <ul>
            <li>Gained proficiency in five web-development languages (HTML, CSS, JavaScript, PHP, mySQL) & two
                frameworks
                (Vue, Laravel), learning them from scratch to design & develop a responsive website for the project
                to
                host the curriculum & a dictionary, thereby expanding access to learning resources.
            </li>
            <li>Employed AI-powered tools to code more efficiently & design engaging visual activities,
                demonstrating an
                innovative approach to problem-solving.
            </li>
            <li>Collaborated with a professional web developer to handle back-end tasks such as server management,
                indicating
                abilities in team-building & project management.
            </li>
        </ul>

        <h3>
            <div>Lexicography & Language Documentation</div>
        </h3>
        <ul>
            <li>Authored a digital pocket dictionary of Palestinian Arabic, curating a repository of over 4,000
                terms.
            </li>
            <li>Led a team to document the dialect on Wiktionary, coordinating the commission of audio files from
                native
                speakers & mastering the Wiki markup language to ensure consistency across 3,000+ entries.
            </li>
            <li>Designed a dictionary for the project website using a mySQL database, reinforcing the project's
                online resources.
            </li>
        </ul>
    </div>

    <h1>
        <div>Work</div>
        <div>Trabajo</div>
        <div>Travail</div>
        <div>仕事</div>
        <div>عمل</div>
        <div>کار</div>
        <div>աշխատանք</div>
    </h1>

    <h2>
        <div>al-Shabaka: the Palestinian Policy Network</div>
    </h2>
    <h3>
        <div>Assistant Editor</div>
        <div>June — August 2021</div>
    </h3>
    <p>Assisted the Commissioning Editor reviewing policy briefs about Palestine. Copyedit all incoming pieces,
        communicating
        with authors & staff to maintain the site’s content flow on schedule. Developed a tool to visualize the
        institution’s content since its inception & identify gaps in coverage & published my own piece for the
        think-tank.</p>

    <h2>
        <div>the Educational Bookshop</div>
        <div>Jerusalem</div>
    </h2>
    <h3>
        <div>Salesman</div>
        <div>October 2018 — December 2019</div>
    </h3>
    <p>Managed an independent English-language bookstore specializing in Middle East topics & political literature,
        making specialized recommendations to customers like diplomats & international NGO workers in Palestine.</p>

    <h2>
        <div>Peace Accords Matrix</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>Research Assistant (Barometer Project)</div>
        <div>May 2017 — December 2018</div>
    </h3>
    <p>Wrote weekly analyses, briefings, translations & summaries in English — amounting to around 200 pages in
        total — about the Colombian Accords & negotiations, analyzing material entirely from primary sources in
        Spanish.</p>

    <h2>
        <div>Creative Associates International</div>
        <div>Washington, D.C.</div>
    </h2>
    <h3>
        <div>Research Assistant (Communities in Transition Division)</div>
        <div>May — July 2018</div>
    </h3>
    <p>Assisted the director of the development company’s Latin America campaign, doing research on current &
        past projects in the area & the landscape of foreign aid in the region; contributed to translation &
        budgeting work.</p>

    <h2>
        <div>WVFI</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>Permanent Staff & Radio Host</div>
        <div>August 2016 — May 2018</div>
    </h3>
    <p>Managed the radio station’s daily activities. Curated the station’s online literary journal, Mindset, &
        coordinated
        the publication of its print form. Conducted interviews of well-known musicians while hosting yearly
        fundraiser.</p>

    <h2>
        <div>the Observer</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>Associate Editor</div>
        <div>August 2015 — May 2018</div>
    </h3>
    <p>Proofread, edit articles & design layout for the culture & arts section of the newspaper on a weekly basis;
        contribute my own weekly articles on a variety of contemporary topics, primarily music.</p>

    <h2>
        <div>Tiny Mix Tapes</div>
    </h2>
    <h3>
        <div>Staff Writer</div>
        <div>September 2017 — December 2019</div>
    </h3>

    <h2>
        <div>Fundación Natividad de los Andes</div>
        <div>San José de Chimbo, Ecuador</div>
    </h2>
    <h3>
        <div>English Language Instructor</div>
        <div>June — August 2016</div>
    </h3>
    <p>Taught English as a foreign language to students from ages 9 to 45 in a rural setting & developed a
        sustainable
        curriculum for the annual summer program, spearheading the writing of the institution’s free textbook.</p>

    <h1>Publications</h1>

    <h2>
        <div></div>
        <div>al-Shabaka</div>
    </h2>
    <h3>
        <a href="#">Focus On: Cracking Down on Resistance</a>
        <div>August 2021</div>
    </h3>

    <h2>
        <div></div>
        <div>it-Tafsīr</div>
    </h2>
    <h3>
        <a href="#">What Happened to Palestinian Workers During COVID-19?</a>
        <div>July 2021</div>
    </h3>

    <h2>
        <div></div>
        <div>Tiny Mix Tapes</div>
    </h2>
    <h3>
        <a href="#">Streaming platforms, the Culture Industry & the Commodification of Accessibility</a>
        <div>December 2019</div>
    </h3>

    <h2>
        <div></div>
        <div>el Universal</div>
    </h2>
    <h3>
        <a href="#">Crónica de un cartagenero en Ramala</a>
        <div>May 2019</div>
    </h3>

    {{--        RESEARCH --}}
    <h1>Research</h1>

    <h2>
        <div>Master's Thesis</div>
        <div>London, UK</div>
    </h2>
    <h3>
        <div>Between a Rock & a Hard Place: Political Identity among Armenians in Palestine & Israel</div>
        <div>2022</div>
    </h3>

    <h2>
        <div>Bachelor's Thesis</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>New Mythologies: a Field Guide to the Semiotics of Capitalism</div>
        <div>2020</div>
    </h3>

    <h2>
        <div>Research Project</div>
        <div>San Basilio de Palenque, Colombia</div>
    </h2>
    <h3>
        <div>Place-Making & Claims-Making among the Palenquero in Post-Conflict Colombia</div>
        <div>2016 — 2018</div>
    </h3>

    <h1>Leadership</h1>

    <h2>
        <div>Worker Participation Committee</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>Local Conditions Commissioner</div>
        <div>Spring 2016 — Spring 2018</div>
    </h3>
    <p>Supervised the University’s licensing policy so as to ensure that merchandise is not produced in factories
        abroad that violate international labor treaties.</p>

    <h2>
        <div>Office of Student Enrichment</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>Project Director</div>
        <div>Fall 2017 — Spring 2018</div>
    </h3>
    <p>Established a student worker position to serve as the liaison between low-income students & university
        administration
        on matters of policy & practice.</p>

    <h2>
        <div>Notre Dame Students for Worker Justice</div>
        <div>Notre Dame, IN</div>
    </h2>
    <h3>
        <div>Vice-President</div>
        <div>Spring 2016 — Spring 2018</div>
    </h3>
    <p>Coordinated activities to raise awareness among the student body about the University’s labor practices.</p>

    <h1>Honors</h1>

    <h2>
        <div>Dean's Fellow</div>
        <div>Notre Dame, IN</div>
    </h2>

    <h2>
        <div>Dean's List (Honor Roll)</div>
        <div>Notre Dame, IN</div>
    </h2>

@endsection
