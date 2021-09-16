import 'regenerator-runtime';

import CaptchaPlugin from './js/plugins/captcha.plugin';


const PluginManager = window.PluginManager;

PluginManager.register('CaptchaPlugin', CaptchaPlugin, '#google_recaptcha_plugin');

