import get from 'lodash.get';

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

window.trans = trans;
