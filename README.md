# Leapfrog

A plugin that supports offloading files to external buckets, leapfrogging the primary file server.


## Notes

we have an interface and sets of directories we need to consider


wp-config.php should define...
- BUCKET_SERVICE
	- as s3 or gcs
- `GCS_PROJECT_ID` **or** `AWS_ACCESS_KEY`
- `GCS_KEY_FILE_PATH` **or** `AWS_SECRET_KEY`
- If `AWS`, `AWS_REGION`
- `BUCKET_NAME`

## to dos
- [ ] update `register_routes` in `./src/Leapfrog/API/SignedUrlController.php`
- [ ] Add  check_permissions, validate_object_name, and validate_content_type methods to `./src/Leapfrog/API/SignedUrlController.php`
