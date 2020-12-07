module.exports = {
  purge: ["./src/**/*.{vue,js,ts,jsx,tsx}"],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        primary: "#d02838",
        primaryDark: "#af212f",
      },
      opacity: {
        "10": ".1",
        "20": ".2",
        "30": ".3",
        "40": ".4",
        "50": ".5",
        "60": ".6",
        "70": ".7",
        "80": ".8",
        "85": ".85",
        "90": ".9",
      },
      translate: {
        "2full": "200%",
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
