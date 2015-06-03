var ImagesView = Backbone.View.extend({
    className   : 'container-fluid',
    
    initialize: function(options) {
        this.options = options;
        this.options.enableNsfw = false;
        
        this.model = options.model;
        
        this.model.on("add", this.onAddImage, this);
        this.model.on("remove", this.onRemoveImage, this);
    },
    
    events: {
        "click #addNewImage": "onAddNewImage",
        "change #enableNsfw": "onEnableNsfw"
    },
    
    render: function() {
        var template = $("#imagesView").html();
        var html = Mustache.render(template);
        this.$el.html(html)
        
        var self = this;
        this.model.each(function(image) {
            self.appendImage(image);
        });
        
        if(this.options.enableNsfw === true) {
            $('#enableNsfw').prop('checked', true);
        }
        
        return this;
    },
    
    onAddImage: function(image) {
        var imageView = new ImageView({ setId: true, model: image});
        $("#images").prepend(imageView.render().$el);
    },
    
    appendImage: function(image) {
        var imageView = new ImageView({ setId: true, model: image});
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
    },
    
    onEnableNsfw: function(e) {
        var enableNsfw = $("#enableNsfw").is(":checked");
        this.options.enableNsfw = enableNsfw;
        
        var self = this;
        this.model.fetch({
            data: { 
                page: 1,
                enableNsfw: enableNsfw
            },
            success: function() {
                console.log(self.model);
                self.render();
            }
        });
    }
});
