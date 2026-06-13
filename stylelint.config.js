export default {
    extends: [
        'stylelint-config-standard-scss',
        'stylelint-config-standard-vue/scss',
    ],
    customSyntax: 'postcss-html',
    rules: {
        'selector-class-pattern': null,
        'alpha-value-notation': null,
    },
}
