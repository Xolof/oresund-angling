#!/usr/bin/env bash
#
# A template for creating command line scripts taking options, commands
# and arguments.
#
# Exit values:
#  0 on success
#  1 on failure
#


# Name of the script
SCRIPT=$( basename "$0" )

#
# Message to display for usage and help.
#
function usage
{
    local txt=(
"Utility $SCRIPT"
"Create a MySQL database for a Q&A website built with Anax."
"Usage: $SCRIPT reset [user] [password]"
"[user] - Your MySQL user."
"[password] - Your MySQL password."
""
# "Command:"
# " reset               Reset the database."
# ""
"Options:"
"  --help, -h       Print help."
    )

    printf "%s\n" "${txt[@]}"
}



#
# Message to display when bad usage.
#
function badUsage
{
    local message="$1"
    local txt=(
"For an overview of the command, execute:"
"$SCRIPT --help, -h"
    )

    [[ -n $message ]] && printf "%s\n" "$message"

    printf "%s\n" "${txt[@]}"
}


#
# Call the route /all
#
function app-reset
{
    IFS=" "
    read -ra func_args <<< "$@"

    user=${func_args[1]}
    pass=${func_args[2]}

    mysql -u$user -p$pass < "answer_mysql.sql"
    mysql -u$user -p$pass < "question_mysql.sql"
    mysql -u$user -p$pass < "answer-comment_mysql.sql"
    mysql -u$user -p$pass < "question-comment_mysql.sql"
    mysql -u$user -p$pass < "user_mysql.sql"
    mysql -u$user -p$pass < "user-profile_mysql.sql"
    mysql -u$user -p$pass < "tag_mysql.sql"
    mysql -u$user -p$pass < "tag-to-question_mysql.sql"
}


function main {
    # Variable to determine if output should be saved.
    SAVE=0

    # Variable to hold the arguments
    ARGS=()

    #
    # Process options
    #
    while (( $# ))
    do
        case "$1" in

            --help | -h)
                usage
                exit 0
            ;;

            reset          \
            )
                command=$1
                IFS=" "
                read -ra ARGS <<< "$*"

                shift
            ;;

            *)
                shift
            ;;

        esac
    done

    if [[ ${#ARGS} -ne 0 ]]
    then
        if [ $SAVE = 1 ]
        then
            app-"$command" "${ARGS[@]}" > "$(pwd)/saved.data"
            echo "Saved output to file $(pwd)/saved.data"
            exit 0
        else
            app-"$command" "${ARGS[@]}"
            exit 0
        fi
    fi

    badUsage "Option/command not recognized."
    exit 1
}

main "$@"
