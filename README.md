Konkurrent
===

Konkurrent is an experimental prototype WordPress theme that makes use of React and the new WP-API. It means, that instead of loading new page every time you click a link, it uses background AJAX requests to WP REST API to fetch data without reloading the page.

**Please note**, it is STRONGLY recommended that this theme is not used in any production environment. It is purely for educational and testing purposes. This theme is under development so it is not recommended that you rely on any aspect of it.

Installation
===
* Working WordPress Installation
First, prepare a proper WordPress environment:
- You will obviously need a working WordPress installation,
- Set your permlink structure to /%postname%/.

* Theme building 
Unlike other themes, this one uses a build process. JavaScript is an interpreted language, but we need to take certain steps (like transpiling React JSX syntax or SASS CSS syntax) to take the files from development phase to production. You will need the following tools:
- node.js and npm - node is an command line JavaScript interpreter that is used for build process. npm stands for node package manager. You can install both from [nodejs.org](https://nodejs.org/en/),
- [webpack](https://webpack.js.org/) is a build system running on node. Once you install node and npm, type ''' npm install -g webpack ''' to install webpack in your system (''' -g ''' flag installs package globally so you will now have webpack command in your command line).

** Next, you have to set up the theme: **

* Download the theme files to ''' wp-content/themes ''' directory of your WordPress installation. You can clone this repository (git clone git@github.com:pratik028/konkurrent-react-theme.git) or just press download on the right side (remember to unzip).
* In root directory, run npm install to install the node dependencies. npm will take care of the rest (npm installs dependencies listed in the package.json file).
* In Picard directory, run ''' webpack ''' to compile the JavaScript and SASS. Webpack will know what to do, because proper actions are listed in the webpack.config.js

Contributing
===
Pull requests and issues are very much welcome!

Todos
===
A not exhaustive list of all things theme still needs to do:
* Work with different permalink structures.
* Support all permalinks (archives, search etc)

Demo
===
[http://konkurrer.com/](http://konkurrer.com/)
