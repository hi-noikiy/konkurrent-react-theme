var webpack = require('webpack');
var path = require('path');
module.exports = {
    devtool: 'inline-source-map',
    entry  : [
      './assets/scripts/main.js'
    ],
    output : {
        path: path.join(__dirname, 'dist/scripts'),
        filename: 'main.js'
    },
    resolve: {
        modulesDirectories: ['node_modules', 'src'],
        extentions: ['', '.js']
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                loaders: ['babel?presets[]=react,presets[]=es2015,plugins[]=react-html-attrs'],
            }
        ]
    },
   plugins: [
       new webpack.NoErrorsPlugin(),
      //  new webpack.DefinePlugin({
      //   'process.env': {
      //     'NODE_ENV': JSON.stringify('production')
      //   }
      // }),
      // new webpack.optimize.UglifyJsPlugin({
      //   compress:{
      //     warnings: true
      //   }
      // })
   ],
   externals: {
    'Config': JSON.stringify(process.env.ENV === 'production' ? {
      siteUrl: 'http://localhost/konkurrent/',
      jsonUrl: "http://localhost/konkurrent/wp-json/wp/v2/"
    } : {
      siteUrl: 'http://localhost/konkurrent/',
      jsonUrl: "http://localhost/konkurrent/wp-json/wp/v2/"
    })
  }
}
