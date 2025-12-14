<?php
require_once __DIR__ . '/../models/Poll.php';

function createPoll($question, $options = [])
{
    $poll = new Poll();
    return $poll->create($question, $options, true);
}

function voteOnPoll($pollId, $option)
{
    $poll = new Poll();
    return $poll->vote($pollId, $option);
}

function getActivePoll()
{
    $poll = new Poll();
    return $poll->getActive();
}
