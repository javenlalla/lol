var ImageView = Backbone.View.extend({
    tagName: "li",
    
    initialize: function(options) {
        this.model.on("change", this.render, this);
    },
    
    events: {
        "click #delete": "onDelete",
        "click #edit": "onEdit",
        "click #save": "onSave",
    },
    
    render: function() {
        this.$el.attr('id', this.model.id);
        
        var template = $("#imageView").html();
        
        jsonModel = this.model.toJSON();
        jsonModel.hasTags = false;
        if(this.model.has("tags") && this.model.get("tags").length > 0) {
            jsonModel.hasTags = true;
        }
        
        var html = Mustache.render(template, jsonModel);
        
        this.$el.html(html);
        
        return this;
    },
    
    renderEdit: function() {
        var template = $("#editImageView").html();
        
        jsonModel = this.model.toJSON();
        jsonModel = this.processTags(jsonModel);
        // jsonModel.hasTags = false;
        // jsonModel.tagsString = "";
        // if(this.model.has("tags") && this.model.get("tags").length > 0) {
        //     var tags = this.model.get("tags");
        //     var tagsString = '';
        //     for(var i = 0; i < tags.length; i++) {
        //         tagsString += tags[i] + ", ";
        //     }
        //     // tagsString = tagsString.replace(/, +$/, '');
            
        //     jsonModel.tagsString = tagsString.replace(/, +$/, '');
            
        //     jsonModel.hasTags = true;
        // }
        
        var html = Mustache.render(template, jsonModel);
        
        this.$el.html(html);
    },
    
    onDelete: function() {
        this.model.destroy();
    },
    
    onEdit: function() {
        console.log("editing");
        this.renderEdit();
    },
    
    onSave: function() {
        var updateName = this.$el.find(".updateName").val();
        var updateTags = this.$el.find(".updateTags").val();
        
        
        this.model.set("name", updateName);
        this.model.set("tags", updateTags);
        
        var self = this;
        this.model.save({name: updateName}, {
            success: function(model, response) {
                self.render();
            },
            error: function(model, response) {
                console.log('not saved');
                console.log(response);
            }
        });
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
