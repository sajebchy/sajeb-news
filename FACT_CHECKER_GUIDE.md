# Fact Checker Feature - Complete Guide

## Overview

The Fact Checker feature enables your news portal to publish fact-checking articles with automatic Google ClaimReview Schema generation. This helps Google Search recognize and display your fact-checking content in specialized search results.

---

## Features Implemented

### 1. **Fact Checker Category**
- Pre-created "Fact Checker" category automatically seeded in the database
- Each category can be configured as a fact-checking source
- Stores reviewer information (name, URL) at the category level

### 2. **Claim Review Fields on Articles**
- `is_claim_review` - Flag article as a fact-check
- `claim_being_reviewed` - The exact claim being fact-checked
- `claim_rating` - Verdict: True, Mostly True, Partly False, False, Unproven
- `claim_review_evidence` - Detailed analysis and evidence
- `claim_review_date` - Publication date of the fact-check

### 3. **Automatic ClaimReview Schema Generation**
- Google ClaimReview schema automatically generated for fact-check articles
- Includes claim, rating, evidence, reviewer info, and dates
- Improves SEO and enables Google's fact-check rich results

### 4. **Admin UI**
- Easy-to-use form fields in news create/edit pages
- Toggle to show/hide ClaimReview fields based on checkbox
- Form validation for required ClaimReview fields

---

## How to Create a Fact-Check Article

### Step 1: Go to News Creation Page
Navigate to `/admin/news/create`

### Step 2: Fill Basic Information
- **Title**: Enter the news headline (e.g., "Fact Check: COVID-19 Vaccine Safety Claims")
- **Slug**: Auto-generated or manually entered
- **Excerpt**: Brief summary
- **Content**: Main article content (analysis, sources, etc.)
- **Category**: Select "Fact Checker" or any fact-checking category

### Step 3: Enable Fact-Check Mode
- Check the box: **"Mark as Fact-Check Article"**
- This reveals the ClaimReview configuration section

### Step 4: Fill ClaimReview Fields

#### Claim Being Reviewed
Enter the exact claim that you're fact-checking:
```
Example: "COVID-19 vaccines contain 5G microchips"
```

#### Claim Rating
Select the fact-check verdict:
- ✓ **True** - Claim is completely accurate
- ≈ **Mostly True** - Claim is largely accurate with minor inaccuracies
- ⚠ **Partly False** - Claim contains significant falsehoods
- ✗ **False** - Claim is completely false
- ? **Unproven** - Claim cannot be verified with current evidence

#### Fact-Check Evidence & Explanation
Provide detailed analysis including:
- Sources and references
- Verification methods
- Contradictory evidence (if applicable)
- Expert opinions
- Data and statistics

Example:
```
We consulted with epidemiologists from WHO and CDC. 
The vaccine component list from FDA shows no electronic components. 
Multiple studies on 50,000+ vaccinated individuals found no evidence 
of any implants or microchips.
```

#### Review Date
Select when the fact-check was published/reviewed.

### Step 5: Complete Other Fields
- **Featured Image**: Upload relevant image
- **Status**: Publish, Draft, or Schedule
- **Tags**: Add relevant tags
- **Featured/Breaking**: Mark if applicable

### Step 6: Publish
Click **"Create Post"** or **"Update Post"**

---

## Database Schema

### Categories Table (New Fields)
```sql
ALTER TABLE categories ADD COLUMN (
    is_fact_checker BOOLEAN DEFAULT FALSE,
    claim_review_enabled BOOLEAN DEFAULT FALSE,
    claim_rating_scale VARCHAR(255) -- True, Mostly True, Partly False, False, Unproven
    claim_reviewer_name VARCHAR(255),
    claim_reviewer_url VARCHAR(255)
);
```

### News Table (New Fields)
```sql
ALTER TABLE news ADD COLUMN (
    is_claim_review BOOLEAN DEFAULT FALSE,
    claim_being_reviewed TEXT,
    claim_rating VARCHAR(255),
    claim_review_evidence LONGTEXT,
    claim_review_date TIMESTAMP
);
```

---

## ClaimReview Schema Output

When you view a fact-check article on the public site, the page automatically includes a ClaimReview schema:

```json
{
  "@context": "https://schema.org",
  "@type": "ClaimReview",
  "claimReviewed": "COVID-19 vaccines contain 5G microchips",
  "url": "https://sajebnews.com/news/covid-vaccine-microchip-fact-check",
  "reviewRating": {
    "@type": "Rating",
    "ratingValue": "False"
  },
  "author": {
    "@type": "Organization",
    "name": "Sajeb News Fact Check Team",
    "sameAs": "https://sajebnews.com"
  },
  "reviewDate": "2024-02-14T10:30:00Z",
  "reviewBody": "We consulted with epidemiologists from WHO and CDC...",
  "claimFirstAppearance": {
    "@type": "WebPage",
    "url": "https://sajebnews.com/news/covid-vaccine-microchip-fact-check"
  }
}
```

---

## Category Configuration (Optional)

### Fact Checker Categories
You can configure multiple fact-checking categories. Each can have:

1. **Go to**: `/admin/categories/create` or `/admin/categories/{id}/edit`
2. **Enable**: "Mark as Fact-Checking Category" checkbox
3. **Configure**:
   - **Reviewer Name**: Organization conducting fact-checks (e.g., "BBC Verify", "AFP Fact Check")
   - **Reviewer URL**: Link to fact-checker's main page
   - **Rating Scale**: Default rating for claims in this category
4. **Save**: Category is now available for fact-check articles

---

## File Structure

### Core Files Modified/Created

1. **app/Http/Controllers/Admin/NewsController.php**
   - Updated `store()` validation rules
   - Updated `update()` validation rules
   - Added ClaimReview field validation

2. **resources/views/admin/news/create.blade.php**
   - Added ClaimReview section with 4 new form fields
   - Added JavaScript toggle for showing/hiding ClaimReview section
   - Added form field validation feedback

3. **app/Models/News.php**
   - Added 5 new fields to `$fillable` array
   - Added `is_claim_review` and `claim_review_date` to `$casts` array

4. **app/Models/Category.php**
   - Added 5 new fields to `$fillable` array
   - Added `is_fact_checker` and `claim_review_enabled` to `$casts` array

5. **app/Services/SchemaGeneratorService.php**
   - Added `newsClaimReviewSchema()` method
   - Generates complete ClaimReview schema from News model

6. **resources/views/public/news/show.blade.php**
   - Added conditional ClaimReview schema output
   - Shows schema only if `is_claim_review && schema_setting_enabled`

7. **database/migrations/**
   - `add_claim_review_to_categories.php`
   - `add_claim_review_to_news.php`

8. **database/seeders/FactCheckerCategorySeeder.php**
   - Auto-creates "Fact Checker" category on seeding

---

## Validation Rules

### On News Store/Update

```php
'is_claim_review' => 'nullable|boolean',
'claim_being_reviewed' => 'nullable|required_if:is_claim_review,1|string|max:1000',
'claim_rating' => 'nullable|required_if:is_claim_review,1|in:True,Mostly True,Partly False,False,Unproven',
'claim_review_evidence' => 'nullable|required_if:is_claim_review,1|string',
'claim_review_date' => 'nullable|date',
```

**Logic**: 
- All ClaimReview fields are optional by default
- When `is_claim_review=1`, the fields become required
- `claim_rating` must be one of the 5 predefined values
- `claim_review_date` must be a valid date

---

## Testing with Google Rich Results Tool

### Step 1: Create & Publish Article
1. Create a fact-check article as described above
2. Publish it (set status to "Published")
3. Note the article URL

### Step 2: Test Schema
1. Go to: https://search.google.com/test/rich-results
2. Paste your article URL
3. Click "TEST URL"

### Step 3: Expected Results
You should see:
- ✅ ClaimReview schema recognized
- ✅ Claim title, rating, and evidence displayed
- ✅ Reviewer organization name
- ✅ Review date

---

## Best Practices

### 1. **Accuracy & Evidence**
- Cite authoritative sources (WHO, CDC, government agencies)
- Link to original sources in your evidence
- Be objective and factual

### 2. **Timely Publishing**
- Set `claim_review_date` close to when you actually published the fact-check
- This helps Google understand the timeliness

### 3. **Consistent Reviewer**
- Use the same reviewer name across all fact-checks
- This builds authority and credibility

### 4. **Clear Claim Statements**
- Quote the exact claim being reviewed
- Keep claims concise and specific

### 5. **Comprehensive Evidence**
- Explain your methodology
- Provide data, statistics, expert opinions
- Address counterarguments

---

## Troubleshooting

### Issue: ClaimReview Schema Not Appearing

**Solution:**
1. Verify `is_claim_review` is checked in news form
2. Check that `schema_settings.enable_claim_review_schema` is true
3. Test URL on Google Rich Results Tool
4. Check page source for `<script type="application/ld+json">` tag

### Issue: Form Fields Not Required

**Solution:**
1. Verify checkbox is properly toggled
2. Check browser console for JavaScript errors
3. Ensure form is submitted when checkbox is checked

### Issue: Validation Errors on Save

**Solution:**
1. Ensure all required ClaimReview fields are filled
2. `claim_rating` must be exactly one of: True, Mostly True, Partly False, False, Unproven
3. `claim_review_date` must be a valid date

---

## Advanced: Custom Claim Review Category

You can create multiple fact-checking categories for different domains:

### Example Categories:
- **Health Fact Check** - Medical claims
- **Political Fact Check** - Political statements
- **Science Fact Check** - Scientific claims
- **Business Fact Check** - Economic/business claims

Each can have its own reviewer organization name and URL.

---

## Schema Reference

### ClaimReview Properties Included

| Property | Source | Description |
|----------|--------|-------------|
| claimReviewed | claim_being_reviewed | The claim being reviewed |
| url | route('news.show') | Public article URL |
| ratingValue | claim_rating | True/False/etc. |
| author.name | category.claim_reviewer_name | Fact-checker organization |
| author.sameAs | category.claim_reviewer_url | Fact-checker link |
| reviewDate | claim_review_date | Date of review |
| reviewBody | claim_review_evidence | Detailed analysis |
| claimFirstAppearance | article URL | Where claim originated |

---

## Related Features

- **JSON-LD Schemas**: 12 different schema types supported
- **Activity Logging**: Track who creates/edits fact-checks
- **Analytics**: Monitor fact-check article performance
- **SEO Settings**: Configure global schema settings

---

## Support & Updates

For issues or feature requests:
1. Check this guide for solutions
2. Verify all migrations have been run: `php artisan migrate --force`
3. Clear cache: `php artisan cache:clear`
4. Retest on Google Rich Results Tool

---

**Last Updated**: February 14, 2024
**Version**: 1.0
**Status**: ✅ Production Ready
