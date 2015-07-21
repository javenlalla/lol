var Images = Backbone.Collection.extend({
    model: LolImage,
    
    url: "index.php/api/images"
});