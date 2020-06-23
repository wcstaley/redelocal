import $ from 'jquery';
import whatInput from 'what-input';

window.$ = $;

import Foundation from 'foundation-sites';
// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
//import './lib/foundation-explicit-pieces';

import Chart from 'chart.js';
import slick from 'slick-carousel';

$(document).foundation();

import './lib/jquery.inputmask.bundle.min';
import './lib/jquery.tablesorter.min';

import './custom/global';
import './custom/order';
import './custom/account';
import './custom/message';
import './custom/dashboard';