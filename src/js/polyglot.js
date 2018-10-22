/**
 * Translate!
 *
 * @param {String} key
 * @param {Object} [params={}]
 * @return {String}
 */
export default function trans(key, params = {}) {
    if (typeof Polyglot === 'undefined') {
        throw new Error('Polyglot is missing.');
    }

    let translation = Polyglot;
    
    key.split('.').forEach(item => translation = ((typeof translation[item] !== 'undefined') ? translation[item] : key));
    
    for (let param in params) {
        let pattern = `:${param}`;
        let regex = new RegExp(pattern, "g");
        translation = translation.replace(regex, params[param]);
    }

    return translation;
}
