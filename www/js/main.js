/**
 * @TODO:
 *  - Add validation to save and update
 *  - Add confirmation dialog to delete
 * 
 */

var AppRouter = Backbone.Router.extend({
    routes: {
        "": "homeAction",
        "home": "homeAction",
        "browse": "browseAction"
    },
    
    homeAction: function() {
        //@TODO, move this Model to it's own JS file.
        var RandomImage = Backbone.Model.extend({
            url: "index.php/api/images/random",
        });
        
        var image = new RandomImage();
        image.fetch();
        
        //@TODO Refactor random image heading.
        var imageView = new ImageView({
            model: image,
            el: "#content",
            setId: false
        });
        
        imageView.render();
        
        $("#content").prepend("<h1>Random Image</h1>");
    },
    
    browseAction: function() {
        $("#link-browse").addClass("active");
        // $(document).ready(function() {
            var images = new Images();
            images.fetch();
            
            var imagesView = new ImagesView({
                model: images,
                el: "#content"
            });
            
            imagesView.render();
            
            // $("body").append(imagesView.render().$el);
        // });
    }
});

$(document).ready(function() {
    
    var router = new AppRouter();
    Backbone.history.start();
    router.navigate('browse', {trigger: true});

});


