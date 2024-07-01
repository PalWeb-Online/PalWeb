resetPreamble() {
this.root.root = '';
this.term.notes.origin = '';
this.variants = [];
this.components = [];
},
resetCategory() {
this.term.category = '';
this.attributes = [];
this.inflections = [];
this.glosses.forEach((value, index) => {
value.attribute = '';
value.structure = '';
});
this.resetAttribute();
},
resetAttribute() {
this.singPatterns = [];
this.plurPatterns = [];
this.verbPatterns = [];
},

hasAttribute(category) {
const allowedCategories = ['verb', 'noun', 'adjective', 'determiner', 'particle'];
console.log(category);
return allowedCategories.includes(category);
},
hasIdiom(category, attributes) {
const allowedCategories = ['verb', 'noun', 'adjective', 'adverb', 'preposition', 'conjunction', 'phrase'];
const allowedAttributes = ['', 'masculine', 'feminine', 'plural'];
if (allowedCategories.includes(category)) {
// TODO: attributes is an array
// return allowedAttributes.includes(attributes);
return true;
}
},
hasClitic(category, attributes) {
const allowedCategories = ['noun', 'adverb', 'preposition', 'conjunction', 'determiner', 'particle'];
const allowedAttributes = ['', 'masculine', 'feminine', 'plural', 'complementizer'];
if (allowedCategories.includes(category)) {
// TODO: attributes is an array
// return allowedAttributes.includes();
return true;
}
},
hasInterrogative(category, attributes) {
if (category === 'determiner') {
// TODO: attributes is an array
// return ['', 'msc', 'fem'].includes(attributes);
return true;
} else if (['noun', 'adverb'].includes(category)) {
// return attributes === [];
return true;
}
return false;
},


hasInflectionChart(category, attributes) {
const allowedCategories = ['verb', 'noun', 'adverb', 'preposition', 'particle', 'phrase'];
if (term.category === 'verb') {
// TODO: attributes is an array
// return ['pseudo'].includes(attributes);
} else if (allowedCategories.includes(category)) {
return true;
}
return false;
},
