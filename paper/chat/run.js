// Description:
// Runs a command on hubot
// TOTAL VIOLATION of any and all security!
//
// Commands:
// hubot run <command> - runs a command on hubot host
module.exports = function(robot) {
    robot.respond("/RUN (.*)$/i", function(msg) {
        console.log(msg);
        var cmd = msg.match[1];
        msg.send("Running " + cmd);
        var exec = require('child_process').exec;
        exec(cmd, function(error, stdout, stderr) {
            if (error) {
                msg.send(error);
                msg.send(stderr);
            } else {
                msg.send(stdout);
            }
        });
    });
};
