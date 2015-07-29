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
        if($("#link-browse").hasClass("active")) {
            $("#link-browse").removeClass("active");
        }    
        $("#link-index").addClass("active");
        
        //@TODO, move this Model to it's own JS file.
        var RandomImage = Backbone.Model.extend({
            url: "index.php/api/images/random",
        });
        
        var image = new RandomImage();
        image.fetch({
            success: function() {
                var imageView = new RandomImageView({
                    model: image,
                    el: "#content",
                    setId: false
                });
                
                imageView.render();
            }
        });
        
    },
    
    browseAction: function() {
        if($("#link-index").hasClass("active")) {
            $("#link-index").removeClass("active");
        }    
        $("#link-browse").addClass("active");
        // $(document).ready(function() {
            var images = new Images();
            images.fetch({
                data: { 
                    page: 1,
                    enableNsfw: $("#enableNsfw").is(":checked")
                },
                success: function() {
                    var imagesView = new ImagesView({
                        model: images,
                        el: "#content"
                    });
                    
                    imagesView.render();
                    
                    // console.log(images);
                },
                error: function(images, resp, options) {
                    //@TODO: Better error handling.
                    $("#content").html(resp.statusText);
                }
            });

            
            // $("body").append(imagesView.render().$el);
        // });
    }
});

$(document).ready(function() {
    
    var router = new AppRouter();
    Backbone.history.start();

});


