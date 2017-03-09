
function ClientliveFeedContent(client_id, date, track)
{
    $('.animation_image_' + client_id).removeClass('hidden');
    $.ajax({
        url: base_url + 'get-live-feed',
        type: 'POST',
        dataType: 'json',
        data: 'track_no=' + track + '&date=' + date + '&client_id=' + client_id,
        success: function(res) {
            $('.animation_image_' + client_id).addClass('hidden');

            if (res.content == undefined || res.content == null || res.content == 0)
            {

            }
            else
            {
                $("#timeline_" + client_id).html(res.content);
                $('#live_feed_date_' + client_id).val(res.date);
                $('#live_feed_track_' + client_id).val(res.track);

            }

        },
        error: function() {
            console.log('error-in-feed');
        }
    });
}