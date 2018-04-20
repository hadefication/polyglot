/**!
 * Translator class
 * 
 * @author Glen Bangkila
 * @license MIT
 */
export default class Translator 
{
    constructor(key, params, locale = null)
    {
        if (typeof Polyglot === 'undefined') {
            throw new Error('Polyglot is missing.');
        }
        
        let { translations } = Polyglot;

        this.key = key;
        this.result = key;
        this.params = typeof params === 'string' ? {} : params;
        this.locale = typeof params === 'string' ? params : locale;
        this.translations = translations[this.resolveLocale()];
        
    }

    resolveLocale()
    {
        let locale = this.locale;
        let { activeLocale, fallbackLocale } = Polyglot;
        let defaultLocale = (activeLocale == '') ? fallbackLocale : activeLocale;
        return (locale === null) ? defaultLocale : locale;
    }
    
    /**
     * Translate key
     * 
     * @return {void}
     */
    parseKey()
    { 
        if (typeof this.translations !== 'undefined') {
            let trans = this.translations.keys;
            this.key.split('.').forEach(item => trans = ((typeof trans[item] !== 'undefined') ? trans[item] : this.key));
            this.result = trans;
        }
        return this;
    }

    /**
     * Translate string
     * 
     * @param {String} payload                  the string to translate
     * @return {void}
     */
    parseString(payload = null)
    {
        let key = payload == null ? this.key : payload;
        if (typeof this.translations !== 'undefined' && 
            typeof this.translations.strings[key] !== 'undefined') {
            this.result = this.translations.strings[key];
        }
        return this;
    }

    /**
     * Parse params of the translated string
     * 
     * @return {void}
     */
    parseParams()
    {
        let trans = this.result;
        for (let param in this.params) {
            let pattern = `:${param}`;
            let regex = new RegExp(pattern, "g");
            trans = trans.replace(regex, this.params[param]);
        }
        this.result = trans;
        return this;
    }

    /**
     * Return the translated string
     * 
     * @return {String}
     */
    translate()
    {
        this.parseKey()
            .parseString()
            .parseParams()
            .parseString(this.result);
        return this.result;
    }
}