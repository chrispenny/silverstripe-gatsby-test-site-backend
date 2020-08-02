const mix = require('laravel-mix');
// eslint-disable-next-line import/no-extraneous-dependencies
const StyleLintPlugin = require('stylelint-webpack-plugin');

// Set up project folders
const themeFolder = 'themes/app';
const srcFolder = `${themeFolder}/src`;
const distFolder = `${themeFolder}/dist`;

// Windows specific. Set the public resources directory.
// This needs to match the "resources-dir" in the composer.json file
const publicResourcesFolder = `public/_resources/${distFolder}`;

if (process.env.NODE_ENV === 'development') {
  // Setup linting
  mix.webpackConfig({
    module: {
      rules: [
        {
          enforce: 'pre',
          test: /\.(js|vue)$/,
          exclude: /node_modules/,
          loader: 'eslint-loader',
        },
      ],
    },
    plugins: [
      new StyleLintPlugin({
        context: 'themes/app/src/',
        files: ['**/*.{scss,vue}'],
      }),
    ],
    devtool: 'inline-source-map',
  });
  mix.sourceMaps();
}

mix.setPublicPath(distFolder); // Places images processed in scss into themes/app/dist folder
mix.setResourceRoot(`/_resources/${distFolder}/`); // Prefixes urls in processed css with _resources/themes/app/dist

mix.js(`${srcFolder}/js/app.js`, distFolder)
  .sass(`${srcFolder}/scss/app.scss`, distFolder)
  .sass('themes/app/src/scss/editor.scss', distFolder)
  .options({ processCssUrls: true });

if (process.env.NODE_ENV === 'production') {
  mix.minify(`${distFolder}/app.css`)
    .minify(`${distFolder}/app.js`)
    .minify(`${distFolder}/editor.css`);
}
/**
 * Windows specific!!!
 * Because windows can't handle symlinks we need to copy the
 * transpiled dependencies to the public directory.
 * NOTE: This needs to be run after all other transpile actions.
 */
if (process.platform === 'win32') {
  mix.copyDirectory(distFolder, publicResourcesFolder);
}

// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.ts(src, output); <-- Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.standaloneSass('src', output); <-- Faster, but isolated from Webpack.
// mix.fastSass('src', output); <-- Alias for mix.standaloneSass().
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.dev');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   globalVueStyles: file, // Variables file to be imported in every component.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });
