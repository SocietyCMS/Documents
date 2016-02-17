var BrowserSyncPlugin = require('browser-sync-webpack-plugin');
var WebpackPublishPlugin = require('../../WebpackPublishPlugin');


module.exports = {
    context: __dirname + '/Resources/assets',
    entry: [
        './app.js'
    ],

    output: {
        path: __dirname + '/Assets/',
        filename: "bundle.js"
    },

    module: {
        loaders: [
            { test: /\.jsx?$/, exclude: /(node_modules|bower_components)/, loader: 'babel', query: { presets: ['react', 'es2015'], plugins: ['transform-runtime']} },
            { test: /\.vue$/, loader: 'vue' },

            {test: /\.scss$/, loader: "style!css-loader!postcss"},

            {test: /\.png$/, loader: 'url', query: { limit: 25000, prefix: 'img/', name: '[name].[ext]?[hash]'}},
            {test: /\.jpg$/, loader: 'url', query: { limit: 25000, prefix: 'img/', name: '[name].[ext]?[hash]'}},
            {test: /\.gif$/, loader: 'url', query: { limit: 25000, prefix: 'img/', name: '[name].[ext]?[hash]'}},
            {test: /\.svg$/, loader: 'url', query: { limit: 25000, prefix: 'img/', name: '[name].[ext]?[hash]'}},

            {test: /\.(woff(2)?)(\?[a-z0-9=\.]+)?$/, loader: 'url', query: { limit: 25000, prefix: 'font/', name: '[name].[ext]?[hash]'}},
            {test: /\.(eot)(\?[a-z0-9=\.]+)?$/, loader: 'file', query: { prefix: 'font/', name: '[name].[ext]?[hash]'}},
            {test: /\.(ttf)(\?[a-z0-9=\.]+)?$/, loader: 'file', query: { prefix: 'font/', name: '[name].[ext]?[hash]'}},
            {test: /\.(svg)(\?[a-z0-9=\.]+)?$/, loader: 'file', query: { prefix: 'font/', name: '[name].[ext]?[hash]'}}
        ]
    },
    postcss: function () {
        return [
            require('autoprefixer'),
            require('precss'),
            require('postcss-less-vars')
        ];
    },
    plugins: [
        new BrowserSyncPlugin({
            proxy: 'societycms.dev'
        }),
        new WebpackPublishPlugin({
            module: 'Documents'
        })
    ]
};
