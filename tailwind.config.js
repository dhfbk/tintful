module.exports = {
    purge: ['./src/**/*.{vue,js,ts,jsx,tsx}'],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {
            colors: {
                primary: '#21A1FD',
                primaryGradient: 'linear-gradient(145deg, #23acff, #1e91e4)',
                primaryDark: '#0284e2',
                primaryLight: '#63bdfd',
                bgDark: '#33333d',
                tokenDefault: '#ccffff',
                'gray-350': '#c6cbd1',
                dark01dp: '#ffffff0D',
                dark02dp: '#ffffff12',
                dark03dp: '#ffffff14',
                dark04dp: '#ffffff17',
                dark06dp: '#ffffff1C',
                dark08dp: '#ffffff1F',
                PER: '#ffbf00',
                ORG: '#e83f6f',
                LOC: '#2274a5',

                //                 dark02dp	7%
                //                 dark30dp	8%
                //                 dark04dp	9%
                // dark06dp	11%
                // dark08dp	12%
                // dark12dp	14%
                // dark16dp	15%
                // dark24dp	16%
            },
            opacity: {
                '10': '.1',
                '20': '.2',
                '30': '.3',
                '40': '.4',
                '50': '.5',
                '60': '.6',
                '70': '.7',
                '80': '.8',
                '85': '.85',
                '90': '.9',
            },
            translate: {
                '2full': '200%',
                '3full': '300%',
            },
            boxShadow: {
                borderShadow: '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                // cardShadow: '10px 10px 20px #c3c4c8, -10px -10px 20px #ffffff',
                // miniCardShadow: '6px 6px 10px #c3c4c8, -6px -6px 10px #ffffff',
                // buttonShadow: '5px 5px 8px #bebebe, -5px -5px 8px #ffffff',
                // buttonShadowFocus: 'inset 5px 5px 8px #bebebe, inset -5px -5px 8px #ffffff',
                // microShadow: '4px 4px 8px #cacbcf, -4px -4px 8px #ffffff',
                // insetShadow: 'inset 5px 5px 9px #c3c4c8, inset -5px -5px 9px #ffffff',
                // microInsetShadow: 'inset 5px 5px 4px #ced0d4, inset -5px -5px 4px #fcfeff',
                // cardShadowDark: '10px 10px 20px #131921,-10px -10px 20px #2b394d',
                // miniCardShadowDark: '6px 6px 10px #131921, -6px -6px 10px #2b394d',
                // buttonShadowDark: '5px 5px 8px #131921, -5px -5px 8px #2b394d',
                // buttonShadowFocusDark: 'inset 5px 5px 8px #131921, inset -5px -5px 8px #2b394d',
                // insetShadowDark: 'inset 5px 5px 9px #131921, inset -5px -5px 9px #2b394d',
                // microInsetShadowDark: 'inset 5px 5px 6px #131921,inset -5px -5px 6px #2b394d',
                // microShadowDark: '5px 5px 6px #131921, -5px -5px 6px #2b394d',
            },
            maxWidth: {
                xxs: '12rem',
            },
        },
    },
    variants: {
        extend: {
            boxShadow: ['dark'],
        },
    },
    plugins: [],
}
