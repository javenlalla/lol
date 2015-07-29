var RandomImageView = Backbone.View.extend({
    tagName: "div",
    
    className: "img-random-block",
    
    options: {
        setId: true
    },
    
    initialize: function(options) {
        this.model.on("change", this.render, this);
        
        this.options = options;
    },
    
    render: function() {
        if(this.options.setId === true) {
            this.$el.attr('id', this.model.id);
        }
        
        var template = $("#randomImageView").html();
        
        jsonModel = this.model.toJSON();
        jsonModel = this.processTags(jsonModel);
        jsonModel.hasTags = false;
        if(this.model.has("tags") && this.model.get("tags").length > 0) {
            jsonModel.hasTags = true;
        }
        
        var html = Mustache.render(template, jsonModel);
        
        this.$el.html(html);
        
        return this;
    },
    
    
    processTags: function(jsonModel) {
        jsonModel.hasTags = false;
        jsonModel.tagsString = "";
        if(this.model.has("tags") && this.model.get("tags").length > 0) {
            var tags = this.model.get("tags");
            var tagsString = '';
            for(var i = 0; i < tags.length; i++) {
                tagsString += tags[i] + ", ";
            }
            // tagsString = tagsString.replace(/, +$/, '');
            
            jsonModel.tagsString = tagsString.replace(/, +$/, '');
            
            jsonModel.hasTags = true;
        }
        
        return jsonModel;
    }
});
