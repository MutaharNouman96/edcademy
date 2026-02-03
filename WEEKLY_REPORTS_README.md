# Weekly Student Reports Feature

## Overview
The Weekly Student Reports feature automatically sends personalized learning progress reports to students every Monday at 10:00 AM. These comprehensive reports include course progress, achievements, upcoming sessions, and personalized recommendations.

## Features

### 📊 Comprehensive Data Included
- **Learning Statistics**: Courses enrolled, lessons completed, hours learned, current streak
- **Course Progress**: Detailed progress bars and completion status for each enrolled course
- **Weekly Activity**: Summary of learning activities and accomplishments
- **Upcoming Sessions**: Scheduled tutoring sessions and appointments
- **Achievements**: Badges and milestones earned during the week
- **Personalized Recommendations**: AI-driven suggestions for next courses and study groups

### 🎨 Professional Email Design
- Modern, responsive email template
- Clean data visualizations with progress bars
- Achievement badges and icons
- Mobile-optimized layout
- Consistent with Ed-Cademy branding

### ⚙️ Automated Scheduling
- Runs every Monday at 10:00 AM via Laravel scheduler
- Processes all active students (logged in within last month)
- Only sends reports to students with meaningful activity
- Background processing for optimal performance

## Files Created

### Mail Classes
- `app/Mail/WeeklyStudentReportMail.php` - Main mail class for weekly reports

### Email Templates
- `resources/views/emails/weekly-student-report.blade.php` - Professional email template

### Console Commands
- `app/Console/Commands/SendWeeklyStudentReports.php` - Main command for weekly reports
- `app/Console/Commands/TestWeeklyReports.php` - Testing command for development

### Scheduler Integration
- `app/Console/Kernel.php` - Updated to include weekly scheduling

## Usage

### Automatic Scheduling (Production)
The system automatically runs every Monday at 10:00 AM. No manual intervention required.

### Manual Testing
```bash
# Test with a specific student
php artisan test:weekly-reports user@example.com

# Test with random students (up to 3)
php artisan test:weekly-reports
```

### Manual Execution
```bash
# Send reports to all eligible students immediately
php artisan students:send-weekly-reports
```

## Data Sources

The report gathers data from:
- **UserPurchasedItem**: Course enrollments and access
- **Booking**: Upcoming tutoring sessions
- **Course/Lesson**: Progress tracking (currently mocked)
- **Activity Logs**: Learning activities (currently mocked)
- **Achievement System**: Badges and milestones (currently mocked)

## Customization

### Adding Real Data
Replace mock data in `SendWeeklyStudentReports.php` with actual:
1. **Progress Tracking**: Implement lesson completion logging
2. **Time Tracking**: Add video watch time calculations
3. **Activity Logging**: Create activity log system
4. **Achievement System**: Build badge/unlock system
5. **Recommendation Engine**: Add AI/ML recommendations

### Modifying Report Frequency
Edit `app/Console/Kernel.php`:
```php
// Change from weekly to different schedule
$schedule->command('students:send-weekly-reports')
         ->weeklyOn(1, '10:00') // Monday 10 AM
         ->weeklyOn(4, '14:00') // Or Thursday 2 PM
```

### Customizing Email Content
Edit `resources/views/emails/weekly-student-report.blade.php` to:
- Change colors and branding
- Add/remove sections
- Modify layout and styling
- Include additional data fields

## Technical Details

### Queue Processing
- All emails use Laravel queues for performance
- Configurable queue connection in `.env`
- Background processing prevents timeouts

### Error Handling
- Try-catch blocks around all email sending
- Logging of failed deliveries
- Graceful degradation (continues processing other students)

### Performance Considerations
- Batched database queries
- Limited to 3 courses per report to prevent email bloat
- Only sends to active students (recent login activity)
- Skips students with no meaningful activity

## Future Enhancements

### Phase 2 Features
- **Interactive Reports**: Clickable progress charts
- **Custom Time Ranges**: Allow students to choose report frequency
- **Export Options**: PDF downloads of reports
- **Comparative Analytics**: Compare with class averages

### Integration Opportunities
- **Learning Analytics**: Integration with learning management systems
- **Gamification**: Enhanced achievement and badge system
- **Social Features**: Share achievements on social media
- **Mobile App**: Push notifications for key milestones

## Testing

### Email Preview (Local Development)
```php
// In tinker or routes
$student = User::where('email', 'test@example.com')->first();
$reportData = app(SendWeeklyStudentReports::class)->generateStudentReportData($student, now()->startOfWeek(), now()->endOfWeek());
return new WeeklyStudentReportMail($student, $reportData);
```

### Queue Monitoring
```bash
# Check queue status
php artisan queue:work --tries=3

# Monitor failed jobs
php artisan queue:failed
```

## Troubleshooting

### Common Issues
1. **No reports sent**: Check queue worker is running
2. **Missing data**: Verify database connections and data structure
3. **Email delivery**: Check SMTP configuration and spam folders
4. **Performance**: Monitor queue processing and database queries

### Debug Commands
```bash
# Test email sending
php artisan test:weekly-reports admin@ed-cademy.com

# Check scheduled commands
php artisan schedule:list

# Run scheduler manually (for testing)
php artisan schedule:run
```

## Support

For technical support or customization requests:
- Check Laravel logs: `storage/logs/laravel.log`
- Monitor queue failures: `php artisan queue:failed`
- Review email delivery: Check mail service provider logs

---

**Note**: This feature includes mock data for demonstration. Replace with real data sources for production use.