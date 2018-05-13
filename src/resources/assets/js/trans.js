/**
 * Translate keys like laravel helper method trans
 *
 * @param  {String} key         the translation key to translate
 * @param  {Object} [params={}] the params to include in the translation
 * @return {String}             the translated string
 */
var trans = function(key, params = {}) {
    if (typeof Polyglot === 'undefined') {
        throw new Error('Polyglot is missing.');
    }

    let trans = Polyglot;
    
    key.split('.').forEach(item => trans = ((typeof trans[item] !== 'undefined') ? trans[item] : key));
    
    for (let param in params) {
        let pattern = `:${param}`;
        let regex = new RegExp(pattern, "g");
        trans = trans.replace(regex, params[param]);
    }

    return trans;
}

export { trans };
