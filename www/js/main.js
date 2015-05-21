/**
 * @TODO:
 *  - Add validation to save and update
 *  - Add confirmation dialog to delete
 * 
 */

var Image = Backbone.Model.extend({
    urlRoot: "index.php/api/images",
    
});

var Images = Backbone.Collection.extend({
    model: Image,
    
    url: "index.php/api/images"
});


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

// var ImageEditView = Backbone.View.extend({
//     tagName: "li",
    
//     initialize: function(options) {
//         this.model.on("change", this.render, this);
//     },
    
//     events: {
//         "click #save": "onSave"
//     },
    
//     render: function() {
//         this.$el.attr('id', this.model.id);
        
//         var template = $("#imageView").html();
        
//         jsonModel = this.model.toJSON();
//         jsonModel.hasTags = false;
//         if(this.model.has("tags") && this.model.get("tags").length > 0) {
//             jsonModel.hasTags = true;
//         }
        
//         var html = Mustache.render(template, jsonModel);
        
//         this.$el.html(html);
        
//         return this;
//     },
    
//     onSave: function() {
//         console.log("save");
//     }
// });

var ImagesView = Backbone.View.extend({
    className   : 'container-fluid',
    
    initialize: function(options) {
        // console.log(options);
        
        this.model.on("add", this.onAddImage, this);
        this.model.on("remove", this.onRemoveImage, this);
    },
    
    events: {
        "click #addNewImage": "onAddNewImage"
    },
    
    render: function() {
        var template = $("#imagesView").html();
        var html = Mustache.render(template);
        this.$el.html(html)
        
        var self = this;
        this.model.each(function(image) {
            self.onAddImage(image);
        });
        
        return this;
    },
    
    onAddImage: function(image) {
        // console.log(image);
        var imageView = new ImageView({ model: image});
        $("#images").append(imageView.render().$el);
    },
    
    onAddNewImage: function(e) {
        e.preventDefault();
        
        var newImageUrl = $("#newImageUrl").val();
        var newImageName = $("#newImage").val();
        var newImageTags = $("#newImageTags").val();
        var newImage = new Image({
            url: newImageUrl,
            name: newImageName,
            tags: newImageTags
        });
        
        this.model.create(newImage);
    },
    
    onRemoveImage: function(image) {
        this.$("li#" + image.id).remove();
    }
});

$(document).ready(function() {
    var images = new Images();
    images.fetch();
    
    var imagesView = new ImagesView({
        model: images
    });
    
    $("body").append(imagesView.render().$el);
});