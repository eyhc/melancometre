import eslint from "@eslint/js";
import tseslint from 'typescript-eslint';
import tsParser from "@typescript-eslint/parser";
import globals from "globals";

export default [
  eslint.configs.recommended,
  ...tseslint.configs.strict,
  ...tseslint.configs.stylistic,
  {
    files: ["**/*.ts"],

    languageOptions: {
      globals: {
        ...globals.builtin,
        ...globals.nodeBuiltin,
        ...globals.browser,
        ...globals.node,
      },

      parser: tsParser,
      ecmaVersion: 2019,
      sourceType: "module",
    },
  }
];