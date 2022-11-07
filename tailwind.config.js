const colors = require('tailwindcss/colors') 
 
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php', 
        './vendor/savannabits/filament-flatpickr/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: { 
                danger: colors.rose,
                primary: colors.green,
                success: colors.green,
                warning: colors.yellow,
                info: colors.blue,
            }, 
        },
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'), 
    ],
}