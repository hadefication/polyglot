/**
 * Require Polyglot object
 * 
 * @return {void}
 */
const requirePolyglot = () => {
    if (typeof Polyglot == 'undefined') {
        throw new Error('Polyglot is undefined.');
    }
};

/**
 * Require translation key or string
 * 
 * @return {Error}
 */
const requireKeyOrString = () => { throw new Error('Translation key or string is required'); };

/**
 * Translate key
 * 
 * @param {String} key 
 * @param {Object} translations 
 * @param {String} locale 
 * @return {String}
 */
const translateKey = (key, translations, locale) => {
    let translation = translations[locale].keys;
    key.split('.').forEach(item => translation = ((typeof translation[item] !== 'undefined') ? translation[item] : key));
    return translation;
};

/**
 * Translate string
 * 
 * @param {String} string 
 * @param {Object} translations 
 * @param {String} locale 
 * @return {String}
 */
const translationStrings = (string, translations, locale) => {
    const strings = translations[locale].strings;
    return typeof strings[string] == 'undefined' || strings[string] == null ? string : strings[string];
};

/**
 * Replace all defined params
 *  
 * @param {String} translation 
 * @param {Object} params 
 * @return {String}
 */
const replaceParams = (translation, params) => {
    for (let param in params) {
        let pattern = `:${param}`;
        let regex = new RegExp(pattern, "g");
        translation = translation.replace(regex, params[param]);
    }
    return translation;
};

/**
 * Translate key or string
 * 
 * @param {String} key 
 * @param {Object} params 
 * @param {String} locale 
 * @return {String}
 */
const translate = (key = requireKeyOrString(), params = {}, locale = null) => {

    requirePolyglot();

    const { settings, translations } = Polyglot;
    const { locale: configLocale, fallback: fallbackLocale } = settings;
    const settingLocale = configLocale != null ? configLocale : fallbackLocale;
    const finalLocale = locale != null ? locale : settingLocale;

    if (typeof translations[finalLocale] == 'undefined') {
        return key;
    }

    let translation = translateKey(key, translations, finalLocale);

    if (translation == key) {
        translation = translationStrings(key, translations, finalLocale);
    }

    return replaceParams(translation, params);
};


/**
 * Translate!
 *
 * @param {String} key
 * @param {Object} [params={}]
 * @return {String}
 */
export function trans(key, params = {}, locale = null) {
    return translate(key, params, locale);
}

export function trans_choice(key, param = {}) {

}
