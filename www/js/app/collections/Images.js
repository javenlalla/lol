var Images = Backbone.Collection.extend({
    model: Image,
    
    url: "index.php/api/images"
});