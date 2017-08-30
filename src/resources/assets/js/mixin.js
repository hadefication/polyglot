/**
 * Translation Mixin for Vue
 *
 * @type {Object}
 */
const transMixin = {
    methods: {
        /**
         * Tranlate
         *
         * @param  {string} key         the translation key to translate
         * @param  {Object} [params={}] the variables to parsed in the translation string
         * @return {string}             translation
         */
        trans(key, params = {})
        {
            return window.trans(key, params);
        }
    }
};

export default transMixin;
