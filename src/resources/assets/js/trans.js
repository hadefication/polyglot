import get from 'lodash.get';

/**
 * Translate keys like laravel helper method trans
 *
 * @param  {String} key         the translation key to translate
 * @param  {Object} [params={}] the params to include in the translation
 * @return {String}             the translated string
 */
const trans = function(key, params = {}) {
    let trans = get(__TRANSLATIONS__, key, key);

    // Replace all variables with the supplied params
    // if there's any.
    for (let param in params) {
        let pattern = `:${param}`;
        let regex = new RegExp(pattern, "g");
        trans = trans.replace(regex, params[param]);
    }

    return trans;
};

export default trans;
window.trans = trans;
