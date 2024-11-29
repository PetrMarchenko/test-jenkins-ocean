pipeline {
    agent any

    environment {
        PHP_SCRIPT = 'index.php'
    }

    triggers {
        cron('H/20 * * * *')
    }

    stages {
        stage('Check server status') {
            steps {
                configFileProvider([configFile(fileId: '1ef9edfb-ff4a-4640-8478-901e150a9d61', variable: 'CONFIG_FILE')]) {
                    script {
                        def servers = readJSON file: CONFIG_FILE

                        servers.each { server ->
                            def server_name = server.server_name
                            def public_key = server.public_key
                            def exec_command = server.exec_command

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
    }
}
