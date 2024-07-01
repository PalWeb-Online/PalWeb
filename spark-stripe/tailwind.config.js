const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },
}
