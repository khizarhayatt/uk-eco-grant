<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <!-- First Column: SMS Form -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4>Send SMS using Twilio</h4>
                        <form id="smsForm">
                            <div class="form-group">
                                <label for="to">Phone Number (UK Format +44)</label>
                                <input type="text" id="to" name="to" class="form-control" placeholder="+44XXXXXXXXXX" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" class="form-control" rows="4" placeholder="Enter your message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send SMS</button>
                        </form>
                        <div id="response"></div> <!-- To show success or error messages -->
                    </div>
                </div>
            </div>

            <!-- Second Column: Recent Messages Table -->
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4>Recent Messages</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th>Message</th>
                                    <th>Date Sent</th>
                                </tr>
                            </thead>
                            <tbody id="recentMessages">
                                <!-- This section will be populated with recent messages -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    // Ajax form submission for sending SMS
    $('#smsForm').on('submit', function(e) {
        e.preventDefault();

        var to = $('#to').val();
        var message = $('#message').val();

        $.ajax({
            url: '<?= base_url('admin/reports/send_sms') ?>', // Your PHP method URL
            type: 'POST',
            data: {
                to: to,
                message: message
            },
            success: function(response) {
                $('#response').html('<div class="alert alert-success">SMS sent successfully!</div>');
                loadRecentMessages(); // Load recent messages after sending an SMS
            },
            error: function(xhr, status, error) {
                $('#response').html('<div class="alert alert-danger">Error sending SMS: ' + xhr.responseText + '</div>');
            }
        });
    });

    // Function to load recent messages and display in table
    function loadRecentMessages() {
        $.ajax({
            url: '<?= base_url('admin/reports/get_recent_messages') ?>', // Your PHP method URL to fetch recent messages
            type: 'GET',
            success: function(response) {
                $('#recentMessages').html(response); // Populate the table with recent messages
            },
            error: function(xhr, status, error) {
                $('#recentMessages').html('<tr><td colspan="3">Error loading recent messages: ' + xhr.responseText + '</td></tr>');
            }
        });
    }

    // Load recent messages when the page loads
    $(document).ready(function() {
        loadRecentMessages();
    });
</script>

</body>
</html>
