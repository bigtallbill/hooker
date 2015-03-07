[![Build Status](https://travis-ci.org/bigtallbill/hooker.svg?branch=master)](https://travis-ci.org/bigtallbill/hooker)

[![Coverage Status](https://img.shields.io/coveralls/bigtallbill/hooker.svg)](https://coveralls.io/r/bigtallbill/hooker?branch=master)

![codeship](https://www.codeship.io/projects/68bf1230-ca93-0131-d192-5e68e7353a88/status)

hooker
======

An extendable php commit hook

Installation
------------

1. Download binary https://github.com/bigtallbill/hooker/releases/tag/0.1.0
2. Copy the binary release to a directory that is loaded by your PATH
3. Make the file executable `chmod a+x hooker`
4. Check installation `which hooker`

Usage
-----

1. open a terminal and cd to the target repository
2. run `hooker install`

This will install a hooker.json config file and also update the commit hook files for this repo to execute hooker

Now when you try to commit something for example. hooker will execute.

hooker.json Config
------------------

The hooker.json config file contains settings for each type of hook.

```
{
    "preCommit": {},
    "commitMsg": {
        "firstWordImperative": true,
        "maxSummaryLength": 50,
        "maxContentLength": 72,
        "summaryIgnoreUrls": true,
        "contentIgnoreUrls": true,
        "lineAfterSummaryMustBeBlank": true,
        "scripts": {
            "after": [
                {
                    "cmd": "runtests.sh",
                    "passGitArgs": false,
                    ""
                }
            ]
        }
    }
}
```

### "scripts"

Every hook type can have the element `scripts`. This allows you to execute arbitrary code that is not a native hooker
check.

```
"scripts": {
    "after": [
        {
            "cmd": "runtests.sh"
        }
    ]
}
```

The "after" key defines when to run the script (currently after is the only one). After means run after hooker's own internal code for that hook.

Then we have an array of scripts to run.

Here is a sample script object:

```
{
    "cmd": "runtests.sh",
    "passGitArgs": false,
    "relativeToRepo": false
}
```

**"cmd"** is the command to execute

**"passGitArgs"** When set to true will append the git arguments onto the scripts existing arguments

**"relativeToRepo"** Execute this script relative to the repo. Currently this is pretty dumb, it will just prepend the repository path to the cmd.


### "commitMsg"

- **firstWordImperative** = Ensure that the first word in a commit message is imperative present-tense
- **maxSummaryLength** = The maximum length of the summary line
- **maxContentLength** = The maximum length of the message body
- **summaryIgnoreUrls** = Ignore length of lines with urls in (useful if you cant help the length of urls)
- **contentIgnoreUrls** = Ignore length of lines with urls in (useful if you cant help the length of urls)
- **lineAfterSummaryMustBeBlank** = Ensure that the line after the summary is blank

