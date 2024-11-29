pipeline {
    agent any

    environment {
        PATH = "/usr/local/bin:${env.PATH}"
        SERVERS_FILE = 'servers.txt'
        PHP_SCRIPT = 'index.php'
    }

    stages {
        stage('Check server status') {
            steps {
                script {
                    def servers = readFile(SERVERS_FILE).split("\n")
                    servers.each { server_line ->
                        def (server_name, public_key, exec_command) = server_line.split('/')

                        echo "Checking service on server: ${server_name}"

                        def result = sh(script: "php ${PHP_SCRIPT} ${public_key}", returnStatus: true)

                        if (result != 0) {
                            echo "Service on server ${server_name} is not available. Restarting server..."

                            sshPublisher(
                                continueOnError: false,
                                failOnError: true,
                                publishers: [
                                    sshPublisherDesc(
                                        configName: server_name,
                                        verbose: true,
                                        transfers: [
                                            sshTransfer(
                                                execCommand: exec_command
                                            )
                                        ]
                                    )
                                ]
                            )
                        } else {
                            echo "Service on server ${server_name} is available."
                        }
                    }
                }
            }
        }
    }

    triggers {
        cron('H/20 * * * *')
    }
}
