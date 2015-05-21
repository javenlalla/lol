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
