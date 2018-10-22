import babel from 'rollup-plugin-babel';
import { uglify } from "rollup-plugin-uglify";
import resolve from 'rollup-plugin-node-resolve';

export default {
    input: 'src/js/index.js',
    output: {
        file: 'src/dist/js/polyglot.js',
        format: 'iife',
        sourceMap: 'inline'
    },
    plugins: [
        resolve(),
        babel({
            exclude: 'node_modules/**' // only transpile our source code
        }),
        uglify()
    ]
};