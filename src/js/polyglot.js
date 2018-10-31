/**
 * Require Polyglot object
 * 
 * @return {void}
 */
const usePolyglot = (locale) => {
    if (typeof Polyglot == 'undefined') {
        throw new Error('Polyglot is undefined.');
    }

    const { settings, translations } = Polyglot;
    const { locale: configLocale, fallback: fallbackLocale } = settings;
    const settingsLocale = configLocale != null ? configLocale : fallbackLocale;
    const finalLocale = locale != null ? locale : settingsLocale;

    return {
        settings, 
        finalLocale,
        translations,
        configLocale,
        fallbackLocale,
    };
};

/**
 * Require translation key or string
 * 
 * @return {Error}
 */
const requireKeyOrString = () => { 
    throw new Error('Translation key or string is required'); 
å};

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
const translateString = (string, translations, locale) => {
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

const removePatternFrom = (string, pattern) => {
    return string.replace(`${pattern}`, '').trim();
};

/**
 * Parse count as single
 * 
 * @param {String} string 
 * @return 
 */
const useSingleChoiceParser = string => {
    const matches = string.match(/\{[0-9]\}+/g);
    if (matches == null) 
        return null;
    const pattern = matches.shift();
    const count = parseInt(pattern.replace(/\{|\}/g, ''));
    const translation = removePatternFrom(string, pattern);
    return { count, translation };
};

/**
 * Parse count as range
 * 
 * @param {String} string 
 * @return {Object}
 */
const useRangeChoiceParser = string => {
    const matches = string.match(/\[(.*?)\]/g);
    if (matches == null) 
        return null;
    const pattern = matches.shift();
    const translation = removePatternFrom(string, pattern);
    const count = pattern.replace(/\[|\]/g, '').split(',').map(item => isNaN(item) ? item : parseInt(item));
    return {count, translation};
};

const useGenericChoiceParse = (string, index) => {
    return {
        translation: string,
        count: index == 0 ? 1 : [2, '*'],
    };
};

/**
 * Parse choices
 * 
 * @param {String} item 
 * @param {Number} index
 * @return {Object}
 */
const useChoiceParser = (item, index) => {
    let test = useSingleChoiceParser(item);
    if(test != null)
        return test;
        
    test = useRangeChoiceParser(item);
    if (test != null)
        return test;

    return useGenericChoiceParse(item, index);
};

/**
 * Select from choices
 * 
 * @param {String} item 
 * @param {Number} selected 
 * @return {String}
 */
const useChoiceSelector = (item, selected) => {
    const { count } = item;

    if (typeof count == 'object') {
        const [start, end] = count;
        if (typeof end == 'string') { 
            return start <= selected; 
        } else { 
            return start <= selected && selected <= end; 
        }
    } else if(typeof count == 'number') {
        return count == selected;
    } else {
        return false;
    }
};

/**
 * Choose from options
 * 
 * @param {String} translation 
 * @param {Number} count 
 * @return {String}
 */
const choose = (translation, count) => {
    const selected = translation.split('|')
                                .map((item, index) => useChoiceParser(item, index))
                                .find(item => useChoiceSelector(item, Math.abs(count)));
    return typeof selected == 'undefined' ? translation : selected.translation;
};

/**
 * Translate key or string
 * 
 * @param {String} key 
 * @param {Object} params 
 * @param {String} locale 
 * @return {String}
 */
const translate = (key = requireKeyOrString(), params = null, locale = null) => {

    const { translations, finalLocale } = usePolyglot(locale);

    if (typeof translations[finalLocale] == 'undefined') {
        return key;
    }

    let translation = translateKey(key, translations, finalLocale);

    if (translation == key) {
        translation = translateString(key, translations, finalLocale);
    }

    return replaceParams(translation, params);
};

const choice = (key = requireKeyOrString(), count = requireKeyOrString(), params = null, locale = null) => {
    const { translations, finalLocale } = usePolyglot(locale);

    if (typeof translations[finalLocale] == 'undefined') {
        return key;
    }

    const translation = replaceParams(translateKey(key, translations, finalLocale), params);

    return choose(translation, count);
};


/**
 * Translate!
 *
 * @param {String} key
 * @param {Object} [params={}]
 * @return {String}
 */
export function trans(key, params = null, locale = null) {
    return translate(key, params, locale);
}

export function trans_choice(key, count, params = null, locale = null) {
    return choice(key, count, params, locale);
}
