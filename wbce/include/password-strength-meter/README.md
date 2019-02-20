Password Strength Meter plugin for jQuery
=========================================

[![Build status][build svg]][build status]
[![Code coverage][coverage svg]][coverage]
[![License][license svg]][license]
[![Latest stable version][releases svg]][releases]
[![Total downloads][downloads svg]][downloads]
[![Code climate][climate svg]][climate]
[![jsDelivr CDN][jsdelivr svg]][jsdelivr]

[![Sauce labs][sauce svg]][sauce]

A password strength meter for jQuery. [Give it a try!][web]

![password example][example]


Installation
------------

Using npm

~~~bash
npm install --save password-strength-meter
~~~

Using bower

~~~bash
bower install --save password-strength-meter
~~~

Or much better, using yarn

~~~bash
yarn add password-strength-meter
~~~

For manual installations check out the [releases][releases] section for tarballs.

Usage
-----

Import the required scripts:

~~~html
<script src="./js/password-strength-meter/password.min.js"></script>
<script src="./js/password-strength-meter/password.min.css"></script>
~~~

Ensure your password field has a wrapper div, like in Bootstrap:

~~~html
<div class="form-group">
  <label for="password">
    Password
  </label>
  <input id="password" type="password" class="form-control" />
</div>
~~~

And call the plugin when you wanna enable it:

~~~javascript
$('#password').password({ /* options */ });
~~~

### Available options

Here's the list of available options you can pass to the `password` plugin:

~~~javascript
$('#password').password({
  shortPass: 'The password is too short',
  badPass: 'Weak; try combining letters & numbers',
  goodPass: 'Medium; try using special charecters',
  strongPass: 'Strong password',
  containsUsername: 'The password contains the username',
  enterPass: 'Type your password',
  showPercent: false,
  showText: true, // shows the text tips
  animate: true, // whether or not to animate the progress bar on input blur/focus
  animateSpeed: 'fast', // the above animation speed
  username: false, // select the username field (selector or jQuery instance) for better password checks
  usernamePartialMatch: true, // whether to check for username partials
  minimumLength: 4 // minimum password length (below this threshold, the score is 0)
});
~~~

### Events

There are two events fired by the `password` plugin:

~~~javascript
$('#password').on('password.score', (e, score) => {
  console.log('Called every time a new score is calculated (this means on every keyup)')
  console.log('Current score is %d', score)
})

$('#password').on('password.text', (e, text, score) => {
  console.log('Called every time the text is changed (less updated than password.score)')
  console.log('Current message is %s with a score of %d', text, score)
})
~~~

Compatiblity
------------

This plugin was originally created in 2010 for jQuery 1.14, and the current relase
has been tested under jQuery 1, 2 & 3.

It should work in all browsers (even IE 666).

![compatibility chart][sauce svg]

Testing
-------

First you'll need to grab the code, as the npm version does not come with the
source files:

~~~bash
git clone https://github.com/elboletaire/password-strength-meter.git
cd password-strength-meter
npm install
npm test
~~~

> Note: tests are just run using jQuery 3.

Why?
----

Why another library? Well, I found this on March 11th (of 2017) cleaning up my
documents folder and noticed I made it in 2010 and never published it, so I
decided to refactor it "a bit" (simply remade it from the ground-up) and publish
it, so others can use it.

Credits
-------

Created by Òscar Casajuana <elboletaire at underave dot net>.

Based on unlicensed work by [Firas Kassem][firas], published in 2007 and modified
by Amin Rajaee in 2009.

This code is licensed under a [GPL 3.0 license][license].

[example]: src/example.png
[firas]: https://phiras.wordpress.com/2009/07/29/password-strength-meter-v-2/
[license]: LICENSE.md
[web]: https://elboletaire.github.io/password-strength-meter/

[build status]: https://travis-ci.org/elboletaire/password-strength-meter
[coverage]: https://codecov.io/gh/elboletaire/password-strength-meter
[license]: https://github.com/elboletaire/password-strength-meter/blob/master/LICENSE.md
[releases]: https://github.com/elboletaire/password-strength-meter/releases
[downloads]: https://www.npmjs.com/package/password-strength-meter
[climate]: https://codeclimate.com/github/elboletaire/password-strength-meter
[jsdelivr]: https://www.jsdelivr.com/package/npm/password-strength-meter
[sauce]: https://saucelabs.com/u/password-strength-manager

[build svg]: https://img.shields.io/travis/elboletaire/password-strength-meter/master.svg?style=flat-square
[coverage svg]: https://img.shields.io/codecov/c/github/elboletaire/password-strength-meter/master.svg?style=flat-square
[license svg]: https://img.shields.io/github/license/elboletaire/password-strength-meter.svg?style=flat-square
[releases svg]: https://img.shields.io/npm/v/password-strength-meter.svg?style=flat-square
[downloads svg]: https://img.shields.io/npm/dt/password-strength-meter.svg?style=flat-square
[climate svg]: https://img.shields.io/codeclimate/github/elboletaire/password-strength-meter.svg?style=flat-square
[jsdelivr svg]: https://data.jsdelivr.com/v1/package/npm/password-strength-meter/badge
[sauce svg]: https://badges.herokuapp.com/sauce/password-strength-manager?style=flat-square
