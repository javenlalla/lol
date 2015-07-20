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
        this.$el.html(html);
        
        // console.log(this.options.grid);
        // this.options.grid.masonry();
        var $grid = $('#images').masonry({
            // options
            itemSelector: '.img-block',
            columnWidth: 200
        });
        
        var self = this;
        this.model.each(function(image) {
            self.appendImage(image, $grid);
        });
        // var $grid = $('#images').imagesLoaded( function() {
        //     $grid.masonry({
        //         // options
        //         itemSelector: '.img-block',
        //         columnWidth: 200
        //     });
        // });
        
        // $grid.imagesLoaded().progress( function() {
        //   $grid.masonry('layout');
        // });
        
        
        
        if(this.options.enableNsfw === true) {
            $('#enableNsfw').prop('checked', true);
        }
        
        
        return this;
    },
    
    onAddImage: function(image) {
        var imageView = new ImageView({ setId: true, model: image});
        var imageItem = imageView.render().$el;

        $("#images").prepend(imageItem).masonry('prepended', imageItem);
        $(imageItem).imagesLoaded().progress( function() {
            // console.log("loading "+image.get('name'));
            $("#images").masonry('layout');
        });
    },
    
    appendImage: function(image) {
        // console.log(this.options.grid);
        // console.log('appended ' + image.get('name'));
        var imageView = new ImageView({ setId: true, model: image});
        var imageItem = imageView.render().$el
        // console.log(imageItem);
        // $("#images").append(imageItem).masonry('appended', imageItem).masonry();
        // this.options.grid.imagesLoaded().progress( function() {
        //   this.options.grid.masonry('layout');
        // });
        $("#images").append(imageItem).masonry('appended', imageItem);
        // console.log(imagesLoaded;
        $("#images").imagesLoaded().progress( function() {
            $("#images").masonry('layout');
        });
    },
    
    onAddNewImage: function(e) {
        e.preventDefault();
        
        var newImageUrl = $("#newImageUrl").val();
        var newImageName = $("#newImage").val();
        var newImageTags = $("#newImageTags").val();
        var newImageNsfw = $("#newImageNsfw").is(':checked');

        var newImage = new LolImage({
            url: newImageUrl,
            name: newImageName,
            tags: newImageTags,
            nsfw: newImageNsfw
        });
        
        newImage.off("change");
        
        this.model.create(newImage, {wait: true});
    },
    
    onRemoveImage: function(image) {
        $('#images').masonry({
            // options
            itemSelector: '.img-block',
            columnWidth: 200
        });
        
        var imageItem = "div#" + image.id;
        var el = $(imageItem);
        // this.$(imageItem).remove().masonry('remove', imageItem);
        $("#images").masonry('remove', el);
        $("#images").masonry('layout');
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
