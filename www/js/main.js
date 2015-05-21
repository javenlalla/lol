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
        console.log('here');
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

});


