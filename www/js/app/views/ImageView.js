var ImageView = Backbone.View.extend({
    tagName: "div",
    
    className: "img-block",
    
    options: {
        setId: true
    },
    
    initialize: function(options) {
        this.model.on("change", this.render, this);
        
        this.options = options;
    },
    
    events: {
        "click .delete": "onDelete",
        "click .edit": "onEdit",
        "click .save": "onSave",
        "click .image-item": "onFocus"//,
        // "mouseleave .image-item": "unFocus"
    },
    
    render: function() {
        if(this.options.setId === true) {
            this.$el.attr('id', this.model.id);
        }
        
        var template = $("#imageView").html();
        
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
        var modalTarget = "#modal-" + this.model.get('id');
        var divTarget = "div#" + this.model.get('id');
        var self = this;
        this.model.destroy({
            success: function(model, response){
                $(modalTarget).modal('hide');
                $(divTarget).remove();
                // $(divTarget).remove().masonry('remove', divTarget);
            }
        });
    },
    
    onEdit: function() {
        // this.renderEdit();
        var modalTarget = "#modal-" + this.model.get('id');
        
        $(modalTarget).find(".image-actions").hide();
        $(modalTarget).find(".image-edit").removeClass('hidden');
    },
    
    onSave: function() {
        // this.undelegateEvents();
        this.model.off("change");
        
        var updateName = this.$el.find(".updateName").val();
        var updateTags = this.$el.find(".updateTags").val();
        var updateNsfw = this.$el.find(".updateNsfw").is(':checked');
        
        
        this.model.set("name", updateName);
        this.model.set("tags", updateTags);
        this.model.set("nsfw", updateNsfw);
        
        var self = this;
        this.model.save({name: updateName}, {
            success: function(model, response) {
                // self.render();
                var modalTarget = "#modal-" + self.model.get('id');
                $(modalTarget).find(".image-actions").show();
                $(modalTarget).find(".image-edit").addClass('hidden');
            },
            error: function(model, response) {
                //@TODO: Better error handling
                console.log('not saved');
                console.log(response);
            }
        });
    },
    
    onFocus: function(e) {
        var modalTarget = "#modal-" + this.model.get('id');
        var modalTargetImage = "#modal-image-" + this.model.get('id');
        
        $(modalTargetImage).attr('src', "i/" + this.model.get('filename')).one("load", function() {
            $(modalTarget).on('shown.bs.modal', function () {
                var modalContentWidth = $(this).find('img').width() + "px";
                    $(this).find('.modal-dialog').css({
                    'width':modalContentWidth
                });
            }).modal();
        });
        
        // console.log($("#modal-image-1").width());
        // $(modalTarget).modal();
        
        
        // var $imageEl = e.target;
        
        // $($imageEl).css({
        //     "z-index": "1000",
        //     "margin": "0 auto",
        //     "position": "relative",
        //     "display": "block"}).attr('src', "i/" + this.model.get('filename'));
    },
    
    unFocus: function(e) {
        var $imageEl = e.target;
        
        $($imageEl).attr('src', "i/" + this.model.get('compressed_filename'));
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
