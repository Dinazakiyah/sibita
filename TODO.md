# TODO: Implement Percentage Setting and Supervisor Validation for Bimbingan

## Completed Tasks
- [x] Add 'percentage' field to Bimbingan model and migration
- [x] Update DosenBimbinganController to allow setting percentage during review
- [x] Update MahasiswaBimbinganController to validate dosen_id is assigned pembimbing
- [x] Update Bimbingan views to display percentage
- [x] Update dashboard views to show percentage
- [x] Run migration for new field

## Remaining Tasks
- [ ] Test the percentage setting and validation
- [ ] Verify dashboards display percentages correctly
- [ ] Ensure proper authorization with BimbinganPolicy

## Testing Steps
1. Login as dosen and review a bimbingan submission
2. Set a percentage value and submit review
3. Verify percentage appears in dosen dashboard
4. Login as mahasiswa and check if percentage is displayed
5. Try to upload bimbingan with unassigned dosen - should fail
6. Verify validation error message appears

## Notes
- Migration has been run successfully
- All code changes have been implemented
- Views updated to display percentage badges
- Validation added to prevent students from choosing unassigned supervisors
