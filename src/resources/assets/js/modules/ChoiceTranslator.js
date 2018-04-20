/**!
 * Translator class
 * 
 * @author Glen Bangkila
 * @license MIT
 */
export default class ChoiceTranslator 
{
    constructor(key, count, params, locale = null)
    {
        if (typeof Polyglot === 'undefined') {
            throw new Error('Polyglot is missing.');
        }
        
        let { translations } = Polyglot;

        this.key = key;
        this.count = count;
        this.result = key;
        this.params = typeof params === 'string' ? {} : params;
        this.locale = typeof params === 'string' ? params : locale;
        this.translations = translations[this.resolveLocale()];
        this.choices = [];
        
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

    resolveChoice(choice)
    {
        let option = {pattern: '', range: [], value: ''};
        let pattern = /\[(.*?)\]|\{.*?\}/g;
        let count = choice.match(pattern);
        if (count !== null) {
            let range = count[0].match(/\[(.*?)\]/);
            if (range !== null) {
                range = range[1].split(',').map(i => parseInt(i));
            }
            option.pattern = count[0];
            option.range = range;
        }
        option.value = choice.replace(pattern, '').trim();
        return option;
    }

    pickOption({pattern, range})
    {
        // let range = pattern.match(/\[(.*?)\]/);
        // if (range !== null) {
        //     range = range[1].split(',').map(i => parseInt(i));
        // }
        console.log(range);
        
        return true;
    }

    parseChoices()
    {
        let choice = this.result.split('|')
                                .map(choice => this.resolveChoice(choice))
                                .find(option => this.pickOption(option));

        console.log(choice);
        if (typeof choice !== 'undefined') {
            this.result = choice.value;
        }

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
            .parseChoices()
            .parseString(this.result);
        return this.result;
    }
}