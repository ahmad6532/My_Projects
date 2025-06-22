module.exports = {
	apps: [
		{
			name: 'qitech-angular',
			script: 'npm',
			args: 'build',
			cwd: '.',
			instances: '1', // Use as many instances as available CPU cores
			exec_mode: 'cluster',
			autorestart: true,
			watch: false, // Disable watch mode in production
			max_memory_restart: '1000M', // Adjust based on your application's memory needs
			log_date_format: 'YYYY-MM-DD HH:mm:ss',
			env: {
				NODE_ENV: 'staging',
				NEW_FEATURE_FLAG: 'true',
			},
		},
	],
};
