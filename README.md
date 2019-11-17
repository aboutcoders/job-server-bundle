# AbcJobServerBundle

[![Build Status](https://travis-ci.org/aboutcoders/job-server-bundle.png?branch=master)](https://travis-ci.org/aboutcoders/job-server-bundle)

A symfony bundle for asynchronous distributed job processing using [php-enqueue](https://github.com/php-enqueue/enqueue-dev) as transport layer.

**Note: This project is still experimental!**

## Demo

You can find a demo [here](https://gitlab.com/hasc/abc-job-demo/).

## Installation

```bash
composer install abc/job-server-bundle
```

## Features

* Asynchronous distributed processing of 
    * Job: a single job
    * Batch: multiple jobs that are processed in parallel
    * Sequence: multiple jobs processed in sequential order
    * Free composition of Job, Sequence, and Batch
* Status information about jobs
* Cancellation and restarting of jobs
* Scheduled processing of jobs (requires [AbcSchedulerBundle](https://github.com/aboutcoders/scheduler-bundle/blob/master/AbcSchedulerBundle.php) 2.x) 
* JSON REST-Api & PHP client library
* [OpenApi](https://www.openapis.org/) documentation

## Getting Started

### Prerequisites
* EnqueueBundle is configured with a transport layer

1. In case you configured a transport with a key different that `default` you have to configure this transport also for the AbcJobServerBundle

	```yaml
	abc_job_server:
	    transport: my_transport_name
	```

2. Create database and database schema

	```bash
	bin/console doctrine:database:create
	bin/console doctrine:schema:create
	```

3. Start the command that processes replies from workers

	```bash
	bin/console abc:process:reply someReplyQueue
	```
 
 4. Create an application that will consume jobs using the [AbcJobWorkerBundle](https://github.com/aboutcoders/job-worker-bundle) that will process jobs.

## Configuration Reference
   
   ```yaml
   abc_job_server:
       # whether to enable the scheduler component
       scheduler:
           enabled: true
   ```

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
