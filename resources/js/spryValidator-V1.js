
; (function ($, window, document, undefined) {

   let _this;
   // plugin constructor
   let SpryValidator = function (container, options) {

      this.container = container;
      this.$container = $(container);
      this.options = options;
      this.hasErrors = false;
      this.validations = {};

      this.drawFunctions = {
         select: this.selectWrapper,
         password: this.passwordWrapper,
         confirm: this.confirmWrapper,
         textarea: this.textareaWrapper,
         radio: this.radioWrapper,
         checkbox: this.checkboxWrapper,
         custom: this.customWrapper,
         default: this.defaultWrapper
      };

      _this = this;

   };

   // the plugin prototype
   SpryValidator.prototype = {
      inputContainerIds: [],

      defaults: {
         none: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            hint: null // describes how to fill this field
         },
         username: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            minAlphaChars: null, // Positive integer value
            maxAlphaChars: null, // Positive integer value
            startAlphaChars: null, // Positive integer value
            hint: null // describes how to fill this field
         },
         integer: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: false, // Boolean
            allowNegative: false, // Boolean
            minValue: null, // Integer value
            maxValue: null, // Integer value
            hint: null // describes how to fill this field
         },
         email: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            hint: null // describes how to fill this field
         },
         date: {
            format: "dd/mm/yyyy", // (other varieties): "mm/dd/yyyy"; "dd/mm/yy"; "yy/mm/dd"; "yyyy/mm/dd"; "mm-dd-yy"; "dd-mm-yy"; "yyyy-mm-dd"; "mm.dd.yyyy"; "dd.mm.yyyy"; "mm/dd/yy";
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean (recommended)
            minValue: null, // Date value in the specified format
            maxValue: null, // Date value in the specified format
            hint: "dd/mm/yyyy" // describes how to fill this field (recommended)
         },
         time: {
            format: "HH:mm", // (other varieties): "HH:mm:ss"; "hh:mm tt"; "hh:mm:ss tt"; "hh:mm t"; "hh:mm;ss t"; (where "tt" stands for am/pm format, and "t" stands for a/p format.)
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: true, // Boolean
            useCharacterMasking: false, // Boolean (recommended)
            minValue: null, // Time value in the specified format
            maxValue: null, // Time value in the specified format
            hint: "HH:mm" // describes how to fill this field (recommended)
         },
         credit_card: {
            format: null, // (other varieties): "visa"; "mastercard"; "amex"; "discover"; "dinersclub" (recommended)
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean (recommended)
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            hint: null // describes how to fill this field (recommended)
         },
         zip_code: {
            format: "zip_custom", // (other varieties): "zip_us9"; "zip_custom"; "zip_canada"; "zip_us5"; "zip_uk" ("zip_custom" is recommended)
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean (recommended)
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            pattern: null, // Custom zip code pattern. Used when format="zip_custom" (recommended)
            hint: null // describes how to fill this field (recommended)
         },
         phone_number: {
            format: 'phone_custom', // (other varieties): "phone_number"; ('phone_custom' is recommended)
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean (recommended)
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            pattern: null, // Custom phone pattern. (recommended)
            hint: null // describes how to fill this field (recommended)

         },
         social_security_number: {
            format: "custom",
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean (recommended)
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            pattern: null, // Custom social security pattern (recommended)
            hint: null // describes how to fill this field (recommended)
         },
         currency: {
            format: "comma_dot", // (other varieties): "dot_comma"
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean
            minValue: null, // Numeric value with two decimal numbers allowed
            maxValue: null, // Numeric value with two decimal numbers allowed
         },
         real: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean
            minValue: null, // Numeric value with decimal numbers
            maxValue: null // Numeric value with decimal numbers
         },
         ip: {
            format: "ipv4_ipv6", // (other varieties): "ipv6"; "ipv4"
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: true, // Boolean
         },
         url: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            hint: null // optional
         },
         checkbox: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            minSelections: null, // Can be modefid by positive integer value
            maxSelections: null // Can be modefid by positive integer value
         },
         radio: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false // Boolean
         },
         select: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            invalidValue: null // accepts a value of an option
         },
         password: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            minChars: "none", // The minimum number of characters required for a valid password.
            maxChars: "none", // The maximum allowable length of the password.
            minAlphaChars: "none", // Minimum number of letters required for a password to be valid.
            maxAlphaChars: "none", // Maximum number of letters required for a password to be valid.
            minUpperAlphaChars: "none", // Minimum number of upper case letters required for a password to be valid.
            maxUpperAlphaChars: "none", // Maximum number of upper case letters required for a password to be valid.
            minSpecialChars: "none", // Minimum number of special characters required for a password to be valid.
            maxSpecialChars: "none", // Maximum number of special characters required for a password to be valid.
            minNumbers: "none", // Minimum number of numbers required for a password to be valid.
            minNumbers: "none" // Maximum number of numbers required for a password to be valid.
         },
         confirm: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false // Boolean
         },
         textarea: {
            validateOn: ['blur', 'change'], // (other varieties): ["blur"]; ["change"]; or both together (["blur", "change"])
            isRequired: false, // Boolean
            useCharacterMasking: false, // Boolean
            minChars: null, // Positive integer value
            maxChars: null, // Positive integer value
            counterType: null, // optional... example => "chars_remaining"
            counterId: null, // optional... example => "my_counter_span"
            hint: null // optional
         },
         errorMessages: {
            required: "A value is required",
            invalid: "Invalid value",
            maxValue: "The maximum value exceeded",
            minValue: "The minimum value not met",
            maxChars: "The maximum number of characters exceeded",
            minChars: "The minimum number of characters not met",
            maxAlphaChars: "The maximum number of alpha characters exceeded",
            minAlphaChars: "The minimum number of alpha characters not met",
            startAlphaChars: "Start with more letters", // (it is recommended to modify it to mention how many letters the user has to start with)
            makeSelection: "Please make a selection",
            invalidSelection: "Invalid selection",
            passwordStrength: "The password strength condition not met",
            passwordCustom: "User defined condition not met",
            invalidConfirm: "The values do not match",
            maxSelections: "The maximum selections exceeded",
            minSelections: "The minimum selections not met"
         },
         validMessages: {
            validText: "",
            validPassword: "",
            validConfirm: "",
            validSelect: "",
            validCheckbox: "",
            validRadio: "",
            validTextarea: "",
            validCustom: ""
         }
      },

      init: function () {
         this.defaults = _this.mergeDeep(this.defaults, this.options);
         this.initSpry();
         this.submitHandler();

         return this;
      },

      isObject: function (item) {
         return (item && typeof item === 'object' && !Array.isArray(item));
      },

      mergeDeep: function (target, ...sources) {
         if (!sources.length) return target;

         const source = sources.shift();

         if (_this.isObject(target) && _this.isObject(source)) {
            for (const key in source) {
               if (_this.isObject(source[key])) {
                  if (!target[key]) Object.assign(target, { [key]: {} });
                  _this.mergeDeep(target[key], source[key]);
               } else {
                  Object.assign(target, { [key]: source[key] });
               }
            }
         }
         return _this.mergeDeep(target, ...sources);
      },

      submitHandler: function () {
         if (_this.$container.prop('tagName') == "FORM") { // Form case
            _this.$container.submit(function (ev) {
               _this.hasErrors = false;
               _this.spryValidation(_this.validations)

               if (_this.hasErrors) {
                  ev.preventDefault();
               }
               _this.onSuccess(_this.validations);

            });
            return true;
         }

         if (this.defaults.submitBtn) {
            $(`${this.defaults.submitBtn}`).on('click', function () {
               _this.hasErrors = false;
               _this.spryValidation(_this.validations)
               if (!_this.hasErrors) {
                  _this.onSuccess(_this.validations);
               }
            })
            return true;
         }
      },

      // check the validation status
      spryValidation: function (sprys) {
         for (const spry of Object.values(sprys)) {
            if (!spry.validate()) {
               _this.hasErrors = true;
            }
         }
      },

      onSuccess: function () {
         if (typeof this.defaults.onSuccess === 'function') {
            if (!this.hasErrors) {
               this.defaults.onSuccess();
            }
         }
      },

      getHtmlOptions: function (input, defaultOptions) {
         let htmlOptions = {};
         let inputAttributes = input[0].attributes;
         if (inputAttributes.maxlength) {
            htmlOptions.maxChars = +inputAttributes["maxlength"].value
         }
         if (inputAttributes.minlength) {
            htmlOptions.minChars = +inputAttributes["minlength"].value
         }
         if (inputAttributes.min) {
            htmlOptions.minValue = +inputAttributes["min"].value
         }
         if (inputAttributes.max) {
            htmlOptions.maxValue = +inputAttributes["max"].value
         }
         if (inputAttributes.required) {
            htmlOptions.isRequired = true;
            input[0].removeAttribute("required");
         }

         return { ...defaultOptions, ...htmlOptions };
      },

      selectWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
               <span class="selectRequiredMsg">${_this.defaults.errorMessages.makeSelection}</span>
               <span class="selectInvalidMsg">${_this.defaults.errorMessages.invalidSelection}</span>
               <span class="selectValidMsg">${_this.defaults.validMessages.validSelect}</span>
         `);

         _this.validationHandler('Select', inputContainerId, [_this.defaults.select])
      },

      passwordWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
               <span class="passwordRequiredMsg">${_this.defaults.errorMessages.required}</span>
               <span class="passwordMinCharsMsg">${_this.defaults.errorMessages.minChars}</span>
               <span class="passwordMaxCharsMsg">${_this.defaults.errorMessages.maxChars}</span>
               <span class="passwordInvalidStrengthMsg">${_this.defaults.errorMessages.passwordStrength}</span>
               <span class="passwordCustomMsg">${_this.defaults.errorMessages.passwordCustom}</span>
               <span class="passwordValidMsg">${_this.defaults.validMessages.validPassword}</span>
         `);

         _this.validationHandler('Password', inputContainerId, [_this.getHtmlOptions(input, _this.defaults.password)])
         _this.idToConfirm = _this.validations[inputContainerId].input.id
      },

      confirmWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
               <span class="confirmRequiredMsg">${_this.defaults.errorMessages.required}</span>
               <span class="confirmInvalidMsg">${_this.defaults.errorMessages.invalidConfirm}</span>
               <span class="confirmValidMsg">${_this.defaults.validMessages.validConfirm}</span>
         `);

         _this.validationHandler('Confirm', inputContainerId, [_this.idToConfirm, _this.getHtmlOptions(input, _this.defaults.confirm)])
      },

      textareaWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${_this.inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
            <span id="my_counter_span"></span>
            <span class="textareaRequiredMsg">${_this.defaults.errorMessages.required}</span>
            <span class="textareaMinCharsMsg">${_this.defaults.errorMessages.minChars}</span>
            <span class="textareaMaxCharsMsg">${_this.defaults.errorMessages.maxChars}</span>
            <span class="textareaValidMsg">${_this.defaults.validMessages.validTextarea}</span>
         `);

         _this.validationHandler('Textarea', inputContainerId, [_this.getHtmlOptions(input, _this.defaults.textarea)])
      },

      radioWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${_this.inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
            <span class="radioRequiredMsg">${_this.defaults.errorMessages.makeSelection}</span>
            <span class="radioValidMsg">${_this.defaults.validMessages.validRadio}</span> 
         `);

         _this.validationHandler('Radio', inputContainerId, [_this.defaults.radio])
      },

      checkboxWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${_this.inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
            <span class="checkboxRequiredMsg">${_this.defaults.errorMessages.makeSelection}</span>
            <span class="checkboxMaxSelectionsMsg">${_this.defaults.errorMessages.maxSelections}</span>
            <span class="checkboxMinSelectionsMsg">${_this.defaults.errorMessages.minSelections}</span>
            <span class="checkboxValidMsg">${_this.defaults.validMessages.validCheckbox}</span>
         `);

         _this.validationHandler('Checkbox', inputContainerId, [_this.defaults.checkbox])
      },

      customWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id=${input.data("id") ? input.data("id") : inputContainerId} class="spryValidate" data-spry=${type}></span>`).after(`
            <span class="textfieldRequiredMsg">${_this.defaults.errorMessages.required}</span>
            <span class="textfieldInvalidFormatMsg">${_this.defaults.errorMessages.invalid}</span>
            <span class="textfieldMaxValueMsg">${_this.defaults.errorMessages.maxValue}</span>
            <span class="textfieldMinValueMsg">${_this.defaults.errorMessages.minValue}</span>
            <span class="textfieldMaxCharsMsg">${_this.defaults.errorMessages.maxChars}</span>
            <span class="textfieldMinCharsMsg">${_this.defaults.errorMessages.minChars}</span>
            <span class="textfieldMaxAlphaCharsMsg">${_this.defaults.errorMessages.maxAlphaChars}</span>
            <span class="textfieldMinAlphaCharsMsg">${_this.defaults.errorMessages.minAlphaChars}</span>
            <span class="customValidMsg">${_this.defaults.validMessages.validCustom}</span>
         `);
         if (_this.defaults[input.data("id")]) {
            _this.validationHandler('TextField', input.data("id"), [type, _this.getHtmlOptions(input, _this.defaults[input.data("id")])])
         } else {
            _this.validationHandler('TextField', inputContainerId, ["none", _this.defaults.none])
            console.log("Check the custom object!")
         }
      },

      defaultWrapper: function (input, inputContainerId, type) {
         input.wrap(`<span id="${inputContainerId}" class="spryValidate" data-spry=${type}></span>`).after(`
               <span class="textfieldRequiredMsg">${_this.defaults.errorMessages.required}</span>
               <span class="textfieldStartAlphaCharsMsg">${_this.defaults.errorMessages.startAlphaChars}</span>
               <span class="textfieldInvalidFormatMsg">${_this.defaults.errorMessages.invalid}</span>
               <span class="textfieldMaxValueMsg">${_this.defaults.errorMessages.maxValue}</span>
               <span class="textfieldMinValueMsg">${_this.defaults.errorMessages.minValue}</span>
               <span class="textfieldMaxCharsMsg">${_this.defaults.errorMessages.maxChars}</span>
               <span class="textfieldMinCharsMsg">${_this.defaults.errorMessages.minChars}</span>
               <span class="textfieldMaxAlphaCharsMsg">${_this.defaults.errorMessages.maxAlphaChars}</span>
               <span class="textfieldMinAlphaCharsMsg">${_this.defaults.errorMessages.minAlphaChars}</span>
               <span class="textfieldValidMsg">${_this.defaults.validMessages.validText}</span>
               `);

         if (_this.defaults[type]) {

            _this.validationHandler('TextField', inputContainerId, [type, _this.getHtmlOptions(input, _this.defaults[type])])
         } else {
            _this.validationHandler('TextField', inputContainerId, ["none", _this.defaults["none"]]);
            console.log(`This data-spry (${type}) type is not currect in span with id (${inputContainerId}), it wont work correctly`)
         }
      },

      validationHandler: function (type, inputContainerId, settings) {
         _this.validations[inputContainerId] = new Spry.Widget[`Validation${type}`](inputContainerId, ...settings);
      },

      initSpry: function () {
         this.$container.find("[data-spry]").each(function () {
            _this.inputContainerId = "spryContainer-" + Math.random().toString(16).slice(2);
            _this.inputContainerIds.push(_this.inputContainerId);

            let input = this;
            let $input = $(this);

            let spryType = input.dataset.spry;
            let parentClassName = input.parentElement.className;
            let parentNodeName = input.parentElement.nodeName;

            if (parentClassName !== "spryValidate" && parentNodeName !== "SPAN") {
               if (_this.drawFunctions[spryType]) {
                  _this.drawFunctions[spryType]($input, _this.inputContainerId, spryType);
               } else {
                  _this.drawFunctions["default"]($input, _this.inputContainerId, spryType);
               }
            }
         });
      }
   }

   SpryValidator.defaults = SpryValidator.prototype.defaults;

   $.fn.spryValidator = function (options) {
      return new SpryValidator(this, options).init();
   };

})(jQuery, window, document);