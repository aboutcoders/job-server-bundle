# AbcJobServerBundle

A symfony bundle for asynchronous distributed job processing using [php-enqueue](https://github.com/php-enqueue/enqueue-dev) as transport layer.

**Note: This project is still experimental!**

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

## Demo

Please take a look at [here](https://gitlab.com/hasc/job-docker-compose) and start a demo application based on docker-compose in a couple of minutes.

## Getting Started

**Prerequisites**
* Configure a Symfony application with AbcJobServerBundle
* Configure the enqueue transport layer

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

3. Setup the broker

	```bash
	bin/console abc:setup-broker -vvv
	```

4. Start the command that processes replies from workers

	```bash
	bin/console enqueue:transport:consume job_reply abc.reply -vvv
	```

5. Start the worker processes

	see AbcWorkerBundle

## Configuration Reference
   
   ```yaml
   abc_job_server:
       # whether to enable the scheduler component
       scheduler:
           enabled: true
   ```

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
