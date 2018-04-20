import Translator from './modules/Translator';
import ChoiceTranslator from './modules/ChoiceTranslator';

/**
 * Translate keys like laravel helper method trans
 *
 * @param  {String} key         the translation key to translate
 * @param  {Object} params      the params to include in the translation
 * @return {String}             the translated string
 * @author {Glen Bangkila}
 */
export function trans(key, params, locale = null) {
    return (new Translator(key, params, locale)).translate();
}

/**
 * Translate keys like laravel helper method trans
 *
 * @param  {String} key         the translation key to translate
 * @param  {Number} count       the translation key to translate
 * @param  {Object} params      the params to include in the translation
 * @return {String}             the translated string
 * @author {Glen Bangkila}
 */
export function trans_choice(key, count, params, locale = null) {
    return (new ChoiceTranslator(key, count, params, locale)).translate();
}

if (typeof window !== 'undefined') {
    window.__ = trans;
    window.__choice = trans_choice;
    window.trans = trans;
    window.trans_choice = trans_choice;
}