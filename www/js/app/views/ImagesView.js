var ImagesView = Backbone.View.extend({
    className   : 'container-fluid',
    
    initialize: function(options) {
        this.model = options.model;
        
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
            self.appendImage(image);
        });
        
        return this;
    },
    
    onAddImage: function(image) {
        var imageView = new ImageView({ model: image});
        $("#images").prepend(imageView.render().$el);
    },
    
    appendImage: function(image) {
        var imageView = new ImageView({ model: image});
        $("#images").append(imageView.render().$el);
    },
    
    onAddNewImage: function(e) {
        e.preventDefault();
        
        var newImageUrl = $("#newImageUrl").val();
        var newImageName = $("#newImage").val();
        var newImageTags = $("#newImageTags").val();
        var newImageNsfw = $("#newImageNsfw").is(':checked');

        var newImage = new Image({
            url: newImageUrl,
            name: newImageName,
            tags: newImageTags,
            nsfw: newImageNsfw
        });
        
        this.model.create(newImage);
    },
    
    onRemoveImage: function(image) {
        this.$("li#" + image.id).remove();
    }
});
