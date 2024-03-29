/**
* 
* Based on
* http://www.extjs.com/forum/showthread.php?t=5106
*
*/


Ext.form.ColorField = function(config){
    Ext.form.ColorField.superclass.constructor.call(this, config);
};

Ext.extend(Ext.form.ColorField, Ext.form.TriggerField,  {
    /**
     * @cfg {String} invalidText
     * The error to display when the color in the field is invalid (defaults to
     * '{value} is not a valid color - it must be in the format {format}').
     */
    invalidText : "{0} is not a valid color - it must be in a the hex format {1}",
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a color wheel icon).
     */
    triggerClass : 'x-form-color-trigger',
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */

    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10", maxlength: "7", autocomplete: "off"},

    // Limit input to hex values
    maskRe: /[a-f0-9]/i,
    regex: /[a-f0-9]/i,

    // private
    validateValue : function(value){
        if(!Ext.form.ColorField.superclass.validateValue.call(this, value)){
            return false;
        }
        if(value.length < 1){ // if it's blank and textfield didn't flag it then it's valid
             return true;
        }

        var parseOK = this.parseColor(value);

        if(!value || (parseOK == false)){
            this.markInvalid(String.format(this.invalidText, value, '#AABBCC'));
            return false;
        }

        return true;
    },

    // private
    // Provides logic to override the default TriggerField.validateBlur which just returns true
    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },

    /**
     * Returns the current value of the color field
     * @return {String} value The color value
     */
    getValue : function(){
        return Ext.form.ColorField.superclass.getValue.call(this) || "";
    },

    /**
     * Sets the value of the color field.  You can pass a string that can be parsed into a valid HTML color
     * <br />Usage:
     * <pre><code>
		colorField.setValue('#FFFFFF');
       </code></pre>
     * @param {String} color The color string
     */
    setValue : function(color){
        Ext.form.ColorField.superclass.setValue.call(this, this.formatColor(color));
    },

    // private
    parseColor : function(value){
	return (!value || (value.substring(0,1) != '#')) ?
		false : true;
    },

    // private
    formatColor : function(value){
	if (value && (this.parseColor(value) == false)) {
		value = '#' + value;
	}

        return value;
    },

    // private
    menuListeners : {
        select: function(e, c){
            this.setValue(c);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus();
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },

    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the ColorPalette
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.menu == null){
            this.menu = new Ext.menu.ColorMenu();
        }

        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));

        this.menu.show(this.el, "tl-bl?");
    }
});